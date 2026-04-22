<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchuelerController extends Controller
{
    public function index(Request $request)
    {
    $user = $request->user();

    if ($user->role === 'admin') {
        // Admin sieht alle Schüler, die er angelegt hat
        $schueler = \App\Models\Schueler::where('admin_id', $user->id)->get();
    } else {
        // Mitarbeiter: Wir gehen über das Profil zur Pivot-Tabelle
        // Eager Loading (.schueler) verhindert viele einzelne DB-Abfragen
        $userWithData = \App\Models\User::with('employeeProfile.schueler')->find($user->id);

        if ($userWithData->employeeProfile) {
            $schueler = $userWithData->employeeProfile->schueler;
        } else {
            $schueler = collect(); // Falls kein Profil existiert, leere Liste
        }
    }

    return response()->json([
        'success' => true,
        'data' => $schueler
    ]);
    }
}