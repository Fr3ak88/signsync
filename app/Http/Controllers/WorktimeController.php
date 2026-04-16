<?php

namespace App\Http\Controllers;

use App\Models\Zeiteintrag;
use App\Models\Monatsabschluss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class WorktimeController extends Controller
{
    /**
     * Übersicht der eigenen Arbeitszeiten (Liste & Filter)
     */
    public function index(Request $request)
{
    $user = Auth::user();
    
    // Prüfen, ob der Archiv-Modus aktiv ist
    $isArchiv = $request->get('view') === 'archiv';

    // Falls Monat/Jahr im Request fehlen, nehmen wir die aktuellen Werte
    $month = (int)$request->input('month', now()->month);
    $year = (int)$request->input('year', now()->year);

    $query = Zeiteintrag::where('user_id', $user->id);

    if ($isArchiv) {
        // ARCHIV: Alle internen Einträge (Arbeit), neueste zuerst
        $eintraege = $query->where('typ', 'arbeit')
            ->orderBy('start_zeit', 'desc')
            ->paginate(100);
    } else {
        // NORMAL: Nur der gewählte Monat
        $eintraege = $query->where('typ', 'arbeit')
            ->whereYear('start_zeit', $year)
            ->whereMonth('start_zeit', $month)
            ->orderBy('start_zeit', 'desc')
            ->paginate(50);
    }

    // Prüfen, ob der aktuell gewählte Monat abgeschlossen ist (nur für UI-Buttons wichtig)
    $isAbgeschlossen = Monatsabschluss::where('user_id', $user->id)
        ->where('monat', $month)
        ->where('jahr', $year) 
        ->where('is_internal', true)
        ->whereNull('cancelled_at')
        ->exists();

    // Export-Erlaubnis (23er Regel)
    $currentDate = now();
    $selectedDate = Carbon::createFromDate($year, $month, 1);
    $istExportErlaubt = ($currentDate->day >= 23 && $currentDate->month == $month && $currentDate->year == $year) 
                        || $selectedDate->isBefore($currentDate->startOfMonth());

    // ALLE ABSCHLÜSSE LADEN: Das ist der Schlüssel für die Beleg-Anzeige in der Tabelle
    // Wir gruppieren sie, damit wir in der Blade-Datei schnell darauf zugreifen können
    $abschluesse = Monatsabschluss::where('user_id', $user->id)
        ->whereNull('cancelled_at')
        ->get()
        ->groupBy(function($item) {
            // Schlüssel-Format: JAHR-MONAT-TYP (0 = extern, 1 = intern)
            return $item->jahr . '-' . $item->monat . '-' . $item->is_internal;
        });

    return view('worktime.index', compact(
        'eintraege', 
        'isAbgeschlossen', 
        'istExportErlaubt', 
        'isArchiv', 
        'abschluesse', 
        'month', 
        'year'
    ));
}

    /**
     * Zeigt das Formular zum Erstellen einer neuen internen Arbeitszeit
     */
    public function create()
    {
        $user = Auth::user();
        
        $isAbgeschlossen = Monatsabschluss::where('user_id', $user->id)
            ->where('monat', now()->month)
            ->where('jahr', now()->year)
            ->where('is_internal', true)
            ->whereNull('cancelled_at')
            ->exists();

        if ($isAbgeschlossen) {
            return redirect()->route('worktime.index')
                ->with('error', 'Der aktuelle Monat ist bereits intern abgeschlossen. Keine neuen Einträge möglich.');
        }

        return view('worktime.create');
    }

    /**
     * Speichern einer neuen Arbeitszeit (POST)
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_zeit' => 'required|date',
            'ende_zeit' => 'required|date|after:start_zeit',
            'taetigkeit' => 'nullable|string|max:255',
        ]);

        $start = Carbon::parse($request->start_zeit);
        $ende = Carbon::parse($request->ende_zeit);
        
        $isAbgeschlossen = Monatsabschluss::where('user_id', Auth::id())
            ->where('monat', $start->month)
            ->where('jahr', $start->year)
            ->where('is_internal', true)
            ->whereNull('cancelled_at')
            ->exists();

        if ($isAbgeschlossen) {
            return redirect()->back()->with('error', 'Dieser Monat ist bereits intern abgeschlossen.');
        }

        $bruttoMinuten = $start->diffInMinutes($ende);
        $autoPause = 0;
        if ($bruttoMinuten > 540) { $autoPause = 45; }
        elseif ($bruttoMinuten > 360) { $autoPause = 30; }

        $nettoMinuten = $bruttoMinuten - $autoPause;
        if ($nettoMinuten > 600) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Verstoß gegen Arbeitszeitgesetz: Über 10 Stunden Netto-Arbeitszeit sind unzulässig.');
        }

        Zeiteintrag::create([
            'user_id' => Auth::id(),
            'typ' => 'arbeit',
            'start_zeit' => $request->start_zeit,
            'ende_zeit' => $request->ende_zeit,
            'pause_minuten' => $autoPause,
            'notiz' => $request->taetigkeit, 
        ]);

        return redirect()->route('worktime.index')->with('success', 'Arbeitszeit erfolgreich erfasst.');
    }

    /**
     * PDF Export: Archivierung & Download
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $month = (int)$request->get('month', now()->format('m'));
        $year = (int)$request->get('year', now()->year);
        $signature = $request->get('signature');
        $isReDownload = $request->has('re_download');

        if ($isReDownload) {
            $abschluss = Monatsabschluss::where('user_id', $user->id)
                ->where('monat', $month)
                ->where('jahr', $year)
                ->where('is_internal', true)
                ->whereNull('cancelled_at')
                ->first();

            if (!$abschluss || !$abschluss->pdf_path) {
                return back()->with('error', 'Archiv-Datei nicht gefunden.');
            }
        } else {
            if (!$signature) {
                return back()->with('error', 'Unterschrift fehlt.');
            }

            $exists = Monatsabschluss::where('user_id', $user->id)
                ->where('monat', $month)
                ->where('jahr', $year)
                ->where('is_internal', true)
                ->whereNull('cancelled_at')
                ->exists();

            if ($exists) {
                return back()->with('error', 'Dieser Zeitraum wurde bereits abgeschlossen.');
            }

            $currentDate = now();
            $isPastMonth = Carbon::createFromDate($year, $month, 1)->isBefore($currentDate->startOfMonth());
            if ($currentDate->day < 23 && !$isPastMonth) {
                return back()->with('error', 'Abschluss erst ab dem 23. des Monats möglich.');
            }

            $eintraege = Zeiteintrag::where('user_id', $user->id)
                ->where('typ', 'arbeit')
                ->whereYear('start_zeit', $year)
                ->whereMonth('start_zeit', $month)
                ->orderBy('start_zeit', 'asc')
                ->get();

            if ($eintraege->isEmpty()) {
                return back()->with('error', 'Keine Einträge vorhanden.');
            }

            // --- GoBD TRANSAKTION ---
            $abschluss = DB::transaction(function () use ($user, $month, $year, $signature, $eintraege) {
                
                // 1. Beleg-Code generieren (Eindeutig für diesen Export)
                $belegCode = strtoupper(substr(hash('sha256', $user->id . now() . microtime()), 0, 12));

                // 2. Abschluss anlegen (mit vorläufigem Hash)
                $newAbschluss = Monatsabschluss::create([
                    'user_id' => $user->id,
                    'monat'   => $month,
                    'jahr'    => $year,
                    'is_internal' => true,
                    'ist_abgeschlossen' => true,
                    'employee_signatur' => $signature,
                    'abgeschlossen_am' => now(),
                    'file_hash' => $belegCode, 
                ]);

                // 3. Einträge versiegeln
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

                $date = Carbon::createFromDate($year, $month, 1);
                $totalMinutes = $eintraege->sum(function($e) {
                    $brutto = Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit));
                    return $brutto - ($e->pause_minuten ?? 0);
                });

                // 4. PDF generieren - Mit belegCode für die Anzeige
                $pdf = Pdf::loadView('worktime.pdf', [
                    'eintraege' => $eintraege,
                    'user' => $user,
                    'monthName' => $date->locale('de')->monthName,
                    'year' => $year,
                    'totalHours' => number_format($totalMinutes / 60, 2, ',', '.'),
                    'signature' => $signature,
                    'abschluss' => $newAbschluss,
                    'belegCode' => $belegCode 
                ]);

                $pdfOutput = $pdf->output();
                $fileName = "private/archive/arbeitsnachweis_{$user->id}_{$year}_{$month}_" . time() . ".pdf";
                
                Storage::disk('local')->put($fileName, $pdfOutput);

                // 5. Finales Update (Echter Datei-Hash)
                $newAbschluss->update([
                    'pdf_path' => $fileName,
                    'file_hash' => $belegCode
                ]);

                return $newAbschluss;
            });
        }

        return Storage::disk('local')->download(
            $abschluss->pdf_path, 
            "Arbeitszeitnachweis_{$user->name}_{$year}_{$month}.pdf"
        );
    }

    public function archiv(Request $request)
{
    $user = Auth::user();
    
    // Query startet nur für diesen User
    $query = Monatsabschluss::where('user_id', $user->id);

    // Filter-Logik (wie bei Admin, nur für den User selbst)
    if ($request->filled('type')) {
        $query->where('is_internal', $request->type === 'intern');
    }
    if ($request->filled('month')) {
        $query->where('monat', $request->month);
    }
    if ($request->filled('year')) {
        $query->where('jahr', $request->year);
    }

    $archiv = $query->orderBy('jahr', 'desc')
                    ->orderBy('monat', 'desc')
                    ->paginate(15);

    return view('employees.index', compact('archiv'));
}

}