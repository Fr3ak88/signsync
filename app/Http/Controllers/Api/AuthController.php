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

    // 2. User suchen
    $user = \App\Models\User::where('email', $request->email)->first();

    // 3. Existenz & Passwort prüfen (Verhindert "on null" Fehler)
    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
    }

    // 4. Schüler-Beziehung sicher abrufen
    $schuelerName = 'Kein Schüler zugewiesen';
    $schuelerId = null;

    try {
        // Prüfen, ob die Beziehung im Model definiert ist und Daten liefert
        if (method_exists($user, 'schueler')) {
            $relation = $user->schueler()->first();
            
            if ($relation) {
                $schuelerId = $relation->id;
                // Hier versuchen wir zu entschlüsseln
                try {
                    $schuelerName = decrypt($relation->name);
                } catch (\Exception $e) {
                    // Falls Entschlüsselung fehlschlägt, nimm den rohen Wert
                    $schuelerName = "Verschlüsselungsfehler / " . substr($relation->name, 0, 10) . "...";
                }
            }
        } else {
            $schuelerName = "Fehler: Beziehung 'schueler' nicht im User-Model definiert";
        }
    } catch (\Exception $e) {
        $schuelerName = "Allgemeiner Fehler beim Laden des Schülers";
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