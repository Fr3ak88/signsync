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
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required', 
    ]);

    // 1. User suchen
    $user = User::where('email', $request->email)->first();

    // 2. WICHTIG: Erst prüfen, ob der User ÜBERHAUPT existiert
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Die Zugangsdaten sind falsch.'], 401);
    }

    // 3. Erst wenn wir sicher sind, dass $user NICHT null ist, die Beziehung laden
    // Wir nutzen try-catch, falls die Verschlüsselung im Schueler-Model knallt
    $klartextName = 'Kein Schüler zugewiesen';
    $schuelerEintrag = $user->schueler()->first();

    if ($schuelerEintrag) {
        try {
            // Falls du den Cast "encrypted" im Model hast, reicht: $schuelerEintrag->name
            // Falls nicht, nutzen wir hier decrypt() manuell:
            $klartextName = decrypt($schuelerEintrag->name);
        } catch (\Exception $e) {
            $klartextName = 'Name konnte nicht entschlüsselt werden';
        }
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'company_name' => $user->company_name,
            'schueler_id' => $schuelerEintrag ? $schuelerEintrag->id : null,
            'schueler_name' => $klartextName,
        ]
    ]);
}
}