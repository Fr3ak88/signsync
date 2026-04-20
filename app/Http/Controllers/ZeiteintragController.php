<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use App\Models\Zeiteintrag;
use App\Models\Monatsabschluss;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ZeiteintragController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Zeiteintrag::with(['schueler', 'user']);

        $aktuellerMonatStr = $request->get('month', now()->format('Y-m'));
        $date = Carbon::parse($aktuellerMonatStr);

        if ($request->filled('month')) {
            $query->whereYear('start_zeit', $date->year)
                  ->whereMonth('start_zeit', $date->month);
        }

        if ($request->filled('typ')) {
            if ($request->typ === 'extern') {
                $query->whereIn('typ', ['begleitung', 'leistung']);
            } elseif ($request->typ === 'arbeit') {
                $query->where('typ', 'arbeit');
            }
        }

        if ($user->role === 'admin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('admin_id', $user->id)
                  ->orWhere('company', $user->company);
            });

            $eintraege = $query->latest('start_zeit')->paginate(15)->appends($request->query());
            return view('zeiteintraege.index', compact('eintraege'));
        } 
        
        $query->where('user_id', $user->id);
        $eintraege = $query->latest('start_zeit')->get(); 

        $abschluss = Monatsabschluss::where('user_id', $user->id)
            ->where('monat', $date->month)
            ->where('jahr', $date->year)
            ->where('is_internal', false)
            ->whereNull('cancelled_at')
            ->first();

        $istGesperrt = false;
        if ($date->isCurrentMonth()) {
            $istGesperrt = now()->lt($date->copy()->endOfMonth()->subDays(7));
        }

        return view('zeiteintraege.index_employee', compact(
            'eintraege', 
            'abschluss', 
            'aktuellerMonatStr', 
            'istGesperrt'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'employee') {
            $employee = Employee::with('schueler')->where('user_id', $user->id)->first();
            $schueler = $employee ? $employee->schueler : collect();
        } else {
            $schueler = Schueler::where('admin_id', $user->id)->get();
        }
        
        return view('zeiteintraege.create', compact('schueler'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'schueler_id' => 'nullable|exists:schuelers,id', 
            'start_zeit'  => 'required|date',
            'ende_zeit'   => 'required|date|after:start_zeit',
            'notiz'       => 'nullable|string',
            'typ'         => 'nullable|string',
            'pause_minuten' => 'nullable|integer|min:0'
        ]);

        $start = Carbon::parse($data['start_zeit']);
        $ende = Carbon::parse($data['ende_zeit']);
        $istIntern = ($request->typ === 'arbeit'); 

        $istGesperrt = Monatsabschluss::where('user_id', Auth::id())
            ->where('monat', $start->month)
            ->where('jahr', $start->year)
            ->where('is_internal', $istIntern)
            ->whereNull('cancelled_at')
            ->exists();

        if ($istGesperrt) {
            $typName = $istIntern ? 'Arbeitsnachweis (intern)' : 'Leistungsnachweis (extern)';
            return redirect()->back()->with('error', "Eintrag nicht möglich: Der {$typName} wurde bereits versiegelt.");
        }

        // --- Automatischer Pausenabzug (ArbZG §4) ---
        $bruttoMinuten = $start->diffInMinutes($ende);
        $autoPause = 0;
        if ($bruttoMinuten > 540) { $autoPause = 45; }
        elseif ($bruttoMinuten > 360) { $autoPause = 30; }
        
        $finalePause = max($autoPause, (int)($request->pause_minuten ?? 0));

        // --- 10-Stunden-Sperre (ArbZG §3) ---
        $nettoMinuten = $bruttoMinuten - $finalePause;
        if ($nettoMinuten > 600) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Verstoß gegen Arbeitszeitgesetz: Die tägliche Netto-Arbeitszeit darf 10 Stunden nicht überschreiten (aktuell: ' . number_format($nettoMinuten/60, 2, ',', '.') . ' h).');
        }

        $zeiteintrag = new Zeiteintrag();
        $zeiteintrag->user_id = Auth::id();
        $zeiteintrag->schueler_id = $data['schueler_id'];
        $zeiteintrag->start_zeit = $data['start_zeit'];
        $zeiteintrag->ende_zeit = $data['ende_zeit'];
        $zeiteintrag->pause_minuten = $finalePause;
        $zeiteintrag->notiz = $data['notiz'];
        $zeiteintrag->typ = $request->typ ?? 'leistung';
        $zeiteintrag->is_locked = false; 
        $zeiteintrag->save();
        
        return redirect()->route('zeiteintraege.index')->with('success', 'Eintrag gespeichert (Pause: ' . $finalePause . ' Min).');
    }

    public function update(Request $request, $id)
    {
        $eintrag = Zeiteintrag::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Nur Administratoren können Einträge korrigieren.');
        }

        $start = Carbon::parse($request->start_zeit);
        $ende = Carbon::parse($request->ende_zeit);
        $istIntern = ($eintrag->typ === 'arbeit');

        $istFinalisiert = Monatsabschluss::where('user_id', $eintrag->user_id)
            ->where('monat', $start->month)
            ->where('jahr', $start->year)
            ->where('is_internal', $istIntern)
            ->whereNull('cancelled_at')
            ->exists();

        if ($istFinalisiert) {
            return redirect()->back()->with('error', "Gesperrt: Der Monat ist versiegelt. Bitte erst stornieren.");
        }

        $data = $request->validate([
            'schueler_id'    => 'nullable|exists:schuelers,id', 
            'start_zeit'     => 'required|date',
            'ende_zeit'      => 'required|date|after:start_zeit',
            'pause_minuten'  => 'nullable|integer|min:0',
            'notiz'          => 'nullable|string',
            'change_reason'  => 'required|string|min:5',
            'change_comment' => 'nullable|string'
        ]);

        $bruttoMinuten = $start->diffInMinutes($ende);
        $autoPause = 0;
        if ($bruttoMinuten > 540) { $autoPause = 45; }
        elseif ($bruttoMinuten > 360) { $autoPause = 30; }
        
        $finalePause = max($autoPause, (int)($request->pause_minuten ?? 0));
        $nettoMinuten = $bruttoMinuten - $finalePause;

        $logSuffix = ($nettoMinuten > 600) ? " (WARNUNG: >10h)" : "";

        activity()
            ->performedOn($eintrag)
            ->causedBy($user)
            ->withProperties(['grund' => $request->change_reason])
            ->log('Eintrag korrigiert' . $logSuffix);

        $eintrag->update([
            'schueler_id' => $request->schueler_id,
            'start_zeit'  => $request->start_zeit,
            'ende_zeit'   => $request->ende_zeit,
            'pause_minuten' => $finalePause,
            'notiz'       => $request->notiz,
            'is_locked'   => false,
            'content_hash'=> null
        ]);

        return redirect()->route('zeiteintraege.index')->with('success', 'Eintrag korrigiert.');
    }

    public function signMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'signature_data' => 'required|string',
            'schule_unterzeichner' => 'required|string|max:255',
            'employee_signature' => 'required|string',
            'employee_confirm' => 'accepted'
        ]);

        $user = Auth::user();
        $date = Carbon::parse($request->month);
        $today = now();

        if ($date->isCurrentMonth()) {
            $allowedFrom = $today->copy()->endOfMonth()->subDays(7);
            if ($today->lt($allowedFrom)) {
                return redirect()->back()->with('error', 'Abschluss erst ab dem ' . $allowedFrom->format('d.m.Y') . ' möglich.');
            }
        }

        $exists = Monatsabschluss::where('user_id', $user->id)
            ->where('monat', $date->month)
            ->where('jahr', $date->year)
            ->where('is_internal', false)
            ->whereNull('cancelled_at')
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Dieser Monat wurde bereits extern abgeschlossen.');
        }

        $schuleSig = $this->cleanSignature($request->signature_data);
        $employeeSig = $this->cleanSignature($request->employee_signature);

        return DB::transaction(function () use ($user, $date, $schuleSig, $employeeSig, $request) {
            $abschluss = Monatsabschluss::create([
                'user_id' => $user->id,
                'monat' => $date->month,
                'jahr' => $date->year,
                'abgeschlossen_am' => now(),
                'schule_signatur' => $schuleSig,
                'employee_signatur' => $employeeSig,
                'schule_unterzeichner' => $request->schule_unterzeichner,
                'ist_abgeschlossen' => true,
                'is_internal' => false 
            ]);

            $eintraege = Zeiteintrag::where('user_id', $user->id)
                ->whereYear('start_zeit', $date->year)
                ->whereMonth('start_zeit', $date->month)
                ->orderBy('start_zeit', 'asc')
                ->get();

            foreach ($eintraege as $eintrag) {
                if (!$eintrag->is_locked) {
                    $dataString = $eintrag->user_id . $eintrag->start_zeit->format('Y-m-d H:i:s') . $eintrag->ende_zeit->format('Y-m-d H:i:s') . $eintrag->notiz;
                    $eintrag->update([
                        'is_locked' => true,
                        'content_hash' => hash('sha256', $dataString)
                    ]);
                }
            }

            $totalMinutes = $eintraege->sum(function($e) {
                $brutto = Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit));
                return $brutto - ($e->pause_minuten ?? 0);
            });
            $totalHours = $totalMinutes / 60;

            $pdf = Pdf::loadView('zeiteintraege.pdf', [
                'eintraege' => $eintraege,
                'user' => $user,
                'monthName' => $date->locale('de')->monthName,
                'year' => $date->year,
                'totalHours' => $totalHours,
                'abschluss' => $abschluss
            ]);

// 1. Erst den Beleg-Code generieren
$belegCode = strtoupper(substr(hash('sha256', $user->id . now() . microtime()), 0, 12));

// 2. Den Code an die PDF-View übergeben
$pdf = Pdf::loadView('zeiteintraege.pdf', [
    'eintraege' => $eintraege,
    'user' => $user,
    'monthName' => $date->locale('de')->monthName,
    'year' => $date->year,
    'totalHours' => $totalHours,
    'abschluss' => $abschluss,
    'belegCode' => $belegCode // <--- WICHTIG: Damit er im PDF steht
]);

$pdfOutput = $pdf->output();
$fileName = "private/archive/leistungsnachweis_{$user->id}_{$date->year}_{$date->month}_" . now()->timestamp . ".pdf";

// 3. Datei speichern
Storage::disk('local')->put($fileName, $pdfOutput);

// 4. Abschluss updaten - Wir nutzen den belegCode als permanenten file_hash
$abschluss->update([
    'pdf_path' => $fileName,
    'file_hash' => $belegCode 

            ]);

            return redirect()->back()->with('success', 'Leistungsnachweis erfolgreich unterzeichnet und versiegelt!');
        });
    }

    public function destroy($id)
    {
        $eintrag = Zeiteintrag::findOrFail($id);
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Nur Admins dürfen löschen.');
        }

        $date = Carbon::parse($eintrag->start_zeit);
        $istFinalisiert = Monatsabschluss::where('user_id', $eintrag->user_id)
            ->where('monat', $date->month)
            ->where('jahr', $date->year)
            ->where('is_internal', ($eintrag->typ === 'arbeit'))
            ->whereNull('cancelled_at')
            ->exists();

        if ($istFinalisiert) {
            return redirect()->back()->with('error', 'Löschen nicht möglich: Monat ist versiegelt.');
        }

        $eintrag->delete();
        return redirect()->route('zeiteintraege.index')->with('success', 'Eintrag entfernt.');
    }

    public function exportPdf(Request $request)
{
    $user = Auth::user();
    $date = Carbon::parse($request->get('month', now()->format('Y-m')));

    // 1. Einträge für den Zeitraum laden
    $eintraege = Zeiteintrag::where('user_id', $user->id)
        ->whereYear('start_zeit', $date->year)
        ->whereMonth('start_zeit', $date->month)
        ->orderBy('start_zeit', 'asc')
        ->get();

    // 2. Den zugehörigen Monatsabschluss laden (Wichtig für die Unterschrift im PDF!)
    $abschluss = Monatsabschluss::where('user_id', $user->id)
        ->where('monat', $date->month)
        ->where('jahr', $date->year)
        ->where('is_internal', false) // Externer Leistungsnachweis
        ->whereNull('cancelled_at')
        ->first();

    // 3. Netto-Stunden berechnen (Brutto - Pause)
    $totalMinutes = $eintraege->sum(function($e) {
        $brutto = Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit));
        return $brutto - ($e->pause_minuten ?? 0);
    });

    // 4. PDF generieren und alle Variablen übergeben
    $pdf = Pdf::loadView('zeiteintraege.pdf', [
        'eintraege' => $eintraege,
        'user' => $user,
        'monthName' => $date->locale('de')->monthName,
        'year' => $date->year,
        'totalHours' => $totalMinutes / 60,
        'abschluss' => $abschluss // <--- Das hat gefehlt!
    ]);

    return $pdf->download("Leistungsnachweis_{$date->format('Y_m')}.pdf");
}

    private function cleanSignature($data) {
        if (str_contains($data, ',')) { $data = explode(',', $data)[1]; }
        return 'data:image/png;base64,' . str_replace(' ', '+', $data);
    }

    public function edit($id)
    {
        $eintrag = Zeiteintrag::findOrFail($id);
        $user = Auth::user();

        if ($eintrag->user_id !== $user->id && $user->role !== 'admin') { 
            abort(403); 
        }

        return view('zeiteintraege.edit', compact('eintrag'));
    }

    public function show($id)
    {
        $eintrag = Zeiteintrag::with(['schueler', 'user'])->findOrFail($id);
        if (Auth::user()->role !== 'admin' && $eintrag->user_id !== Auth::id()) { abort(403); }
        return view('zeiteintraege.show', compact('eintrag'));
    }
}