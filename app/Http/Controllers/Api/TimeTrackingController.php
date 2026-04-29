<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zeiteintrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
    public function stats()
    {
    $user = Auth::user();
    
    // Aktueller Monat
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    // Alle Einträge des Users für diesen Monat holen
    $eintraege = Zeiteintrag::where('user_id', $user->id)
        ->whereBetween('start_zeit', [$startOfMonth, $endOfMonth])
        ->get();

    $gesamtMinuten = 0;

    foreach ($eintraege as $eintrag) {
        // Differenz in Minuten berechnen
        $dauer = $eintrag->start_zeit->diffInMinutes($eintrag->ende_zeit);
        
        // Pause abziehen
        $netto = $dauer - ($eintrag->pause_minuten ?? 0);
        
        $gesamtMinuten += $netto;
    }

    // Umrechnen in Stunden und Minuten für die Anzeige
    $stunden = floor($gesamtMinuten / 60);
    $minuten = $gesamtMinuten % 60;

    return response()->json([
        'success' => true,
        'data' => [
            'monat' => Carbon::now()->translatedFormat('F Y'),
            'gesamt_minuten' => $gesamtMinuten,
            'formatiert' => "{$stunden}h {$minuten}m",
            'anzahl_eintraege' => $eintraege->count()
        ]
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