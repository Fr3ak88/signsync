<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zeiteintrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimeTrackingController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validierung: 
        // 'typ' muss dabei sein (arbeit oder leistung)
        // 'schueler_id' ist nur Pflicht ('required_if'), wenn der Typ 'leistung' ist.
        $request->validate([
            'typ'          => 'required|in:arbeit,leistung',
            'is_internal'  => 'nullable|string',
            'schueler_id'  => 'required_if:typ,leistung|nullable|exists:schuelers,id',
            'start_zeit'   => 'required|date',
            'ende_zeit'    => 'required|date|after:start_zeit',
            'notiz'        => 'nullable|string'
        ]);

        $employeeId = $user->employeeProfile->id;

        // 2. Sicherheits-Check: Nur bei 'leistung' prüfen, ob der Schüler dem User gehört
        if ($request->typ === 'leistung') {
            if (!$user->employeeProfile->schueler->contains($request->schueler_id)) {
                return response()->json(['message' => 'Nicht berechtigt für diesen Schüler'], 403);
            }
        }

        // 3. Den Eintrag in die Datenbank schreiben
        $entry = Zeiteintrag::create([
            'user_id'       => $user->id,
            'employee_id'   => $employeeId,
            'schueler_id'   => $request->typ === 'leistung' ? $request->schueler_id : null,
            'start_zeit'    => $request->start_zeit,
            'ende_zeit'     => $request->ende_zeit,
            'notiz'         => $request->notiz,
            'pause_minuten' => 0,
            'typ'           => $request->typ, // Speichert 'arbeit' oder 'leistung'
            'is_locked'     => false,
            'is_internal'   => $request->is_internal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Zeiteintrag (' . $request->typ . ') erfolgreich gespeichert.',
            'data'    => $entry
        ], 201);
    }

    /**
     * Einen bestehenden Zeiteintrag aktualisieren.
     */
    public function update(Request $request, $id)
    {
    $user = Auth::user();

    // 1. Den Eintrag finden und sicherstellen, dass er dem User gehört
    $entry = Zeiteintrag::where('user_id', $user->id)->findOrFail($id);

    // 2. Prüfen, ob der Eintrag bereits gesperrt ist
    if ($entry->is_locked) {
        return response()->json([
            'success' => false,
            'message' => 'Dieser Eintrag ist gesperrt und kann nicht mehr geändert werden.'
        ], 403);
    }

    // 3. Validierung (ähnlich wie beim Speichern)
    $request->validate([
        'typ'          => 'required|in:arbeit,leistung',
        'schueler_id'  => 'required_if:typ,leistung|nullable|exists:schuelers,id',
        'start_zeit'   => 'required|date',
        'ende_zeit'    => 'required|date|after:start_zeit',
        'notiz'        => 'nullable|string'
    ]);

    // 4. Sicherheits-Check für Schüler-Berechtigung (nur bei leistung)
    if ($request->typ === 'leistung') {
        if (!$user->employeeProfile->schueler->contains($request->schueler_id)) {
            return response()->json(['message' => 'Nicht berechtigt für diesen Schüler'], 403);
        }
    }

    // 5. Daten aktualisieren
    $entry->update([
        'schueler_id'   => $request->typ === 'leistung' ? $request->schueler_id : null,
        'start_zeit'    => $request->start_zeit,
        'ende_zeit'     => $request->ende_zeit,
        'notiz'         => $request->notiz,
        'typ'           => $request->typ,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Eintrag erfolgreich aktualisiert.',
        'data'    => $entry
    ]);
    }

    /**
     * Liefert Statistiken für den aktuellen Monat.
     */
    public function stats(Request $request)
    {
    $user = Auth::user();
    
    // Monat bestimmen (Standard: aktueller Monat, oder via Request steuerbar)
    $month = $request->query('month', Carbon::now()->month);
    $year = $request->query('year', Carbon::now()->year);

    $eintraege = Zeiteintrag::with('schueler:id,name')
        ->where('user_id', $user->id)
        ->whereMonth('start_zeit', $month)
        ->whereYear('start_zeit', $year)
        ->orderBy('start_zeit', 'asc') // Nach Datum sortiert
        ->get();

    // Summen-Variablen
    $totalLeistung = 0;
    $totalArbeit = 0;

    // Gruppierung nach Datum für die App-Ansicht
    $history = $eintraege->groupBy(function($item) {
        return $item->start_zeit->format('Y-m-d');
    })->map(function($dayGroup) use (&$totalLeistung, &$totalArbeit) {
        return $dayGroup->map(function($eintrag) use (&$totalLeistung, &$totalArbeit) {
            // Dauer berechnen
            $minuten = $eintrag->start_zeit->diffInMinutes($eintrag->ende_zeit) - ($eintrag->pause_minuten ?? 0);
            
            // Zu Gesamtsummen hinzufügen
            if ($eintrag->typ === 'leistung') {
                $totalLeistung += $minuten;
            } else {
                $totalArbeit += $minuten;
            }

            return [
                'id' => $eintrag->id,
                'typ' => $eintrag->typ,
                'von' => $eintrag->start_zeit->format('H:i'),
                'bis' => $eintrag->ende_zeit->format('H:i'),
                'dauer_minuten' => $minuten,
                'schueler' => $eintrag->schueler ? $eintrag->schueler->name : null,
                'notiz' => $eintrag->notiz
            ];
        });
    });

    return response()->json([
        'success' => true,
        'meta' => [
            'zeitraum' => Carbon::create($year, $month)->translatedFormat('F Y'),
            'gesamt_stunden' => round(($totalLeistung + $totalArbeit) / 60, 2),
            'davon_leistung_stunden' => round($totalLeistung / 60, 2),
            'davon_arbeit_stunden' => round($totalArbeit / 60, 2),
        ],
        'details_nach_datum' => $history
    ]);
    }

    /**
     * Einen Zeiteintrag löschen.
     */
    public function destroy($id)
    {
    $user = Auth::user();

    // 1. Eintrag suchen (Sicherstellen, dass er dem User gehört)
    $entry = Zeiteintrag::where('user_id', $user->id)->findOrFail($id);

    // 2. Sperr-Prüfung (Wichtig!)
    if ($entry->is_locked) {
        return response()->json([
            'success' => false,
            'message' => 'Gesperrte Einträge können nicht gelöscht werden. Bitte kontaktieren Sie Ihren Admin für ein Storno.'
        ], 403);
    }

    // 3. Löschen
    $entry->delete();

    return response()->json([
        'success' => true,
        'message' => 'Eintrag wurde erfolgreich gelöscht.'
    ]);
    }
    
    public function index()
    {
        $user = Auth::user();

        $eintraege = Zeiteintrag::with('schueler:id,name')
            ->where('user_id', $user->id)
            ->orderBy('start_zeit', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $eintraege
        ]);
    }
}