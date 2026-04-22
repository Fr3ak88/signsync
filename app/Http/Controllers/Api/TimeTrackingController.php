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