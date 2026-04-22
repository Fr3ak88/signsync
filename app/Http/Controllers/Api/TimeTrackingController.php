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
        
        // 1. Validierung der Daten von der App
        $request->validate([
            'schueler_id' => 'required|exists:schuelers,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'date'        => 'required|date',
            'bemerkung'   => 'nullable|string'
        ]);

        // 2. Den Mitarbeiter (Employee) finden
        $employeeId = $user->employeeProfile->id;

        // 3. Den Eintrag in die Datenbank schreiben
        $entry = Zeiteintrag::create([
            'employee_id' => $employeeId,
            'schueler_id' => $request->schueler_id,
            'admin_id'    => $user->admin_id, // Die Firma zuordnen
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'bemerkung'   => $request->bemerkung,
            'status'      => 'offen', // Standard-Status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Zeiteintrag erfolgreich gespeichert.',
            'data'    => $entry
        ], 201);
    }
}