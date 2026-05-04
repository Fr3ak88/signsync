<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // 1. Validierung
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    // 2. User suchen (mit Eager Loading für das Profil und die Schüler)
    $user = \App\Models\User::with('employeeProfile.schueler')->where('email', $request->email)->first();

    // 3. Existenz & Passwort prüfen
    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
    }

    // 4. Schüler-Daten über das Profil abrufen (wie im SchuelerController)
    $schuelerName = 'Kein Schüler zugewiesen';
    $schuelerId = null;

    // Wir prüfen den Pfad: User -> employeeProfile -> schueler
    if ($user->employeeProfile && $user->employeeProfile->schueler->isNotEmpty()) {
        $relation = $user->employeeProfile->schueler->first();
        $schuelerId = $relation->id;

        try {
            // Versuch der Entschlüsselung
            $schuelerName = decrypt($relation->name);
        } catch (\Exception $e) {
            // Falls der Name bereits Klartext ist oder die Entschlüsselung scheitert
            $schuelerName = $relation->name;
        }
    }

    // 5. Token erstellen
    $token = $user->createToken($request->device_name)->plainTextToken;

    // 6. Antwort senden
    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'company_name' => $user->company_name ?? 'Firma nicht gesetzt',
            'schueler_id' => $schuelerId,
            'schueler_name' => $schuelerName,
        ]
    ]);
}
}