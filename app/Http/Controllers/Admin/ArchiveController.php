<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monatsabschluss;
use App\Models\User;
use App\Models\Zeiteintrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        
        $employees = User::where('admin_id', $admin->id)
            ->where('role', 'employee')
            ->orderBy('name')
            ->get();

        $query = Monatsabschluss::with('user')
            ->whereHas('user', function($q) use ($admin) {
                $q->where('admin_id', $admin->id);
            });

        if ($request->filled('type')) {
            $query->where('is_internal', $request->type === 'intern' ? 1 : 0);
        }

        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $selectedYear = (int)$request->input('year', now()->year);
        $query->where('jahr', $selectedYear);

        if ($request->filled('month')) {
            $query->where('monat', (int)$request->month);
        }

        $archiv = $query->orderBy('jahr', 'desc')
                        ->orderBy('monat', 'desc')
                        ->paginate(20);

        return view('admin.archive.index', compact('archiv', 'employees', 'selectedYear'));
    }

    /**
     * Download mit GoBD-Versiegelung der zugrunde liegenden Daten
     */
    public function download($id)
{
    $abschluss = Monatsabschluss::with('user')->findOrFail($id);
    $currentUser = Auth::user();

    // Security Check
    if ($currentUser->role === 'admin' && $abschluss->user->admin_id !== $currentUser->id) {
        abort(403, 'Unbefugter Zugriff.');
    }

    // --- GoBD AUTO-REPAIR & VERSIEGELUNG ---
    // Falls die Datei schon existiert, aber die Einträge noch nicht gesperrt sind 
    // oder der Hash in der DB fehlt, holen wir das hier nach.
    DB::transaction(function () use ($abschluss) {
        $user = $abschluss->user;
        
        // 1. Alle zugehörigen Einträge finden, die noch nicht gesperrt sind
        $query = Zeiteintrag::where('user_id', $user->id)
            ->whereYear('start_zeit', $abschluss->jahr)
            ->whereMonth('start_zeit', $abschluss->monat);

        if ($abschluss->is_internal) {
            $query->where('typ', 'arbeit');
        } else {
            $query->where('typ', '!=', 'arbeit');
        }

        $eintraege = $query->get();

        foreach ($eintraege as $eintrag) {
            if (!$eintrag->is_locked) {
                $dataString = $eintrag->user_id . 
                              $eintrag->start_zeit->format('Y-m-d H:i:s') . 
                              $eintrag->ende_zeit->format('Y-m-d H:i:s') . 
                              $eintrag->notiz;

                $eintrag->update([
                    'is_locked' => true,
                    'content_hash' => hash('sha256', $dataString)
                ]);
            }
        }

        // 2. Falls Datei existiert, aber file_hash in DB fehlt -> Nach-Versiegeln
        if (!empty($abschluss->pdf_path) && Storage::disk('local')->exists($abschluss->pdf_path)) {
            if (empty($abschluss->file_hash)) {
                $pdfContent = Storage::disk('local')->get($abschluss->pdf_path);
                $abschluss->update([
                    'file_hash' => hash('sha256', $pdfContent)
                ]);
            }
        }
    });

    // --- PDF GENERIERUNG (falls Datei komplett fehlt) ---
    if (empty($abschluss->pdf_path) || !Storage::disk('local')->exists($abschluss->pdf_path)) {
        
        $user = $abschluss->user;

        DB::transaction(function () use ($abschluss, $user) {
            $query = Zeiteintrag::where('user_id', $user->id)
                ->whereYear('start_zeit', $abschluss->jahr)
                ->whereMonth('start_zeit', $abschluss->monat);

            if ($abschluss->is_internal) {
                $query->where('typ', 'arbeit');
                $viewPath = 'worktime.pdf'; 
                $prefix = "arbeitsnachweis";
            } else {
                $query->where('typ', '!=', 'arbeit');
                $viewPath = 'zeiteintraege.pdf'; 
                $prefix = "leistungsnachweis";
            }

            $eintraege = $query->orderBy('start_zeit', 'asc')->get();

            // PDF Daten vorbereiten
            $totalMinutes = $eintraege->sum(fn($e) => Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit)));
            $displayDate = Carbon::create($abschluss->jahr, $abschluss->monat, 1);

            $pdf = Pdf::loadView($viewPath, [
                'eintraege' => $eintraege,
                'user' => $user,
                'monthName' => $displayDate->locale('de')->monthName,
                'year' => $abschluss->jahr,
                'totalHours' => number_format($totalMinutes / 60, 2, ',', '.'),
                'signature' => $abschluss->employee_signatur ?? $eintraege->whereNotNull('signature')->first()?->signature,
                'abschluss' => $abschluss
            ]);

            $pdfOutput = $pdf->output();
            $fileName = "private/archive/{$prefix}_{$user->id}_{$abschluss->jahr}_{$abschluss->monat}_" . time() . ".pdf";
            
            // PDF Hash für die Revisionssicherheit erzeugen
            $fileHash = hash('sha256', $pdfOutput);

            Storage::disk('local')->put($fileName, $pdfOutput);

            $abschluss->update([
                'pdf_path' => $fileName,
                'file_hash' => $fileHash // Jetzt aktiv gesetzt
            ]);
        });
    }

    $abschluss->refresh();

    // Download Name generieren
    $displayDate = Carbon::create($abschluss->jahr, $abschluss->monat, 1);
    $downloadName = ($abschluss->is_internal ? 'Arbeitsnachweis' : 'Leistungsnachweis') . 
                    "_{$abschluss->user->name}_" . $displayDate->locale('de')->monthName . "_{$abschluss->jahr}.pdf";

    return Storage::disk('local')->download($abschluss->pdf_path, $downloadName);
}
}