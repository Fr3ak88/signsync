<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zeiteintrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeTrackingController extends Controller
{
    public function store(Request $request)
    {
    $user = Auth::user();
    
    // 1. Validierung (Namen so wie sie von der App/Postman kommen)
    $request->validate([
        'schueler_id' => 'required|exists:schuelers,id',
        'start_zeit'  => 'required', 
        'ende_zeit'   => 'required', 
        'notiz'       => 'nullable|string'
    ]);

    // 2. Den Mitarbeiter (Employee) finden
    $employeeId = $user->employeeProfile->id;
    if (!$user->employeeProfile->schueler->contains($request->schueler_id)) {
    return response()->json(['message' => 'Nicht berechtigt für diesen Schüler'], 403);
    }
    // 3. Den Eintrag in die Datenbank schreiben
    // WICHTIG: Die Keys links müssen EXAKT so heißen wie im $fillable / Datenbank
    $entry = Zeiteintrag::create([
        'user_id'       => $user->id,
        'employee_id'   => $employeeId,
        'schueler_id'   => $request->schueler_id,
        'start_zeit'    => $request->start_zeit, // Korrigiert
        'ende_zeit'     => $request->ende_zeit,  // Korrigiert
        'notiz'         => $request->notiz,      // Korrigiert
        'pause_minuten' => 0,                    // Standardwert aus Fillable
        'typ'           => 'Arbeitszeit',        // Beispielwert für 'typ'
        'is_locked'     => false,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Zeiteintrag erfolgreich gespeichert.',
        'data'    => $entry
    ], 201);
    }

    /**
     * Zeigt die Historie der Zeiteinträge des Nutzers an.
     */
    public function index()
    {
    $user = Auth::user();

    // Wir holen alle Einträge des Nutzers
    // 'with' lädt die Schülerdaten direkt mit (Eager Loading)
    $eintraege = Zeiteintrag::with('schueler:id,name') 
        ->where('user_id', $user->id)
        ->orderBy('start_zeit', 'desc') // Neueste zuerst
        ->limit(50) // Optional: Nur die letzten 50 anzeigen
        ->get();

    return response()->json([
        'success' => true,
        'data'    => $eintraege
    ]);
    }
}