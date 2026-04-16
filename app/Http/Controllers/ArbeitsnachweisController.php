<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zeiteintrag;
use App\Models\Monatsabschluss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class ArbeitsnachweisController extends Controller
{
    /**
     * Admin-Übersicht: Getrennte Stunden nach Intern/Extern
     */
    public function index(Request $request)
    {
        $month = (int)$request->input('month', now()->month);
        $year = (int)$request->input('year', now()->year);

        // 1. Alle User finden, die im Zeitraum Einträge haben
        $userIdsWithEntries = Zeiteintrag::whereMonth('start_zeit', $month)
            ->whereYear('start_zeit', $year)
            ->distinct()
            ->pluck('user_id');

        $employees = User::whereIn('id', $userIdsWithEntries)->get();

        // 2. Aktive (nicht stornierte) Abschlüsse laden
        $abschluesse = DB::table('monats_abschluesse')
            ->where('monat', $month)
            ->where('jahr', $year)
            ->whereNull('cancelled_at') // Wichtig: Stornierte Abschlüsse nicht als "Signiert" zählen
            ->get()
            ->keyBy('user_id');

        // 3. Daten aufbereiten
        $reportData = $employees->map(function($user) use ($abschluesse, $month, $year) {
            
            $eintraege = Zeiteintrag::where('user_id', $user->id)
                ->whereMonth('start_zeit', $month)
                ->whereYear('start_zeit', $year)
                ->get();

            // Interne Minuten (Typ: arbeit)
            $intMinutes = $eintraege->where('typ', 'arbeit')->sum(function($e) {
                return Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit));
            });

            // Externe Minuten (Typ != arbeit)
            $extMinutes = $eintraege->where('typ', '!=', 'arbeit')->sum(function($e) {
                return Carbon::parse($e->start_zeit)->diffInMinutes(Carbon::parse($e->ende_zeit));
            });

            $abschluss = $abschluesse->get($user->id);

            return [
                'user_id'        => $user->id,
                'name'           => $user->name,
                'hours_internal' => number_format($intMinutes / 60, 2, ',', '.'),
                'hours_external' => number_format($extMinutes / 60, 2, ',', '.'),
                'total_hours'    => number_format(($intMinutes + $extMinutes) / 60, 2, ',', '.'),
                'is_signed'      => (bool)$abschluss,
                'signed_at'      => $abschluss ? (Carbon::parse($abschluss->abgeschlossen_am)->format('d.m.Y H:i')) : null,
            ];
        });

        $sortedReports = $reportData->sortBy([
            ['is_signed', 'asc'],
            ['name', 'asc'],
        ]);

        return view('admin.arbeitsnachweise.index', [
            'reports' => $sortedReports,
            'selectedMonth' => $month,
            'selectedYear' => $year
        ]);
    }

    /**
     * Detailansicht eines Mitarbeiters für einen Monat
     */
    public function show(Request $request, User $user)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $eintraege = Zeiteintrag::with('schueler')
            ->where('user_id', $user->id)
            ->whereMonth('start_zeit', $month)
            ->whereYear('start_zeit', $year)
            ->orderBy('start_zeit', 'asc')
            ->get();

        $abschluss = DB::table('monats_abschluesse')
            ->where('user_id', $user->id)
            ->where('monat', (int)$month)
            ->where('jahr', (int)$year)
            ->whereNull('cancelled_at') // Nur den aktuell gültigen Abschluss zeigen
            ->first();

        return view('admin.arbeitsnachweise.show', compact('user', 'eintraege', 'month', 'year', 'abschluss'));
    }

    /**
     * Storno-Funktion: Hebt die Versiegelung auf
     */
    public function cancel(Request $request, $id)
{
    DB::beginTransaction();
    try {
        $abschluss = Monatsabschluss::findOrFail($id);
        
        $request->validate([
            'cancel_reason' => 'required|string|min:5'
        ]);

        // 1. Den Abschluss-Beleg stornieren
        $abschluss->cancelled_at = now();
        $abschluss->cancel_reason = $request->cancel_reason;
        $abschluss->ist_abgeschlossen = false; // Hier setzen wir den Status zurück
        $abschluss->save();

        // 2. NUR die zum Nachweis gehörenden Einträge freigeben
$query = Zeiteintrag::where('user_id', $abschluss->user_id)
    ->whereYear('start_zeit', $abschluss->jahr)
    ->whereMonth('start_zeit', $abschluss->monat);

// WICHTIG: Nur den Typ entsperren, der zum stornierten Abschluss gehört
if ($abschluss->is_internal) {
    // Falls es ein interner Abschluss war: Nur 'arbeit' entsperren
    $query->where('typ', 'arbeit');
} else {
    // Falls es ein externer Abschluss war: Alles außer 'arbeit' entsperren
    $query->where('typ', '!=', 'arbeit');
}

$query->update([
    'is_locked' => false,
    'content_hash' => null 
]);
        // 3. Activity Log
        activity()
            ->performedOn($abschluss)
            ->causedBy(auth()->user())
            ->withProperties(['grund' => $request->cancel_reason])
            ->log('Monatsabschluss storniert und Zeiteinträge entsperrt.');

        DB::commit();
        return redirect()->back()->with('success', 'Storno erfolgreich. Die 6 Einträge wurden wieder freigegeben.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Fehler: ' . $e->getMessage());
    }
}
}