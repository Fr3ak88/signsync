<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Schueler;
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

    // 1. User mit Beziehung laden
    $user = \App\Models\User::with('employeeProfile.schueler')->where('email', $request->email)->first();

    // 2. Check ob User existiert
    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
    }

    $schuelerName = 'Kein Schüler zugewiesen';
    $schuelerId = null;

    // 3. Den Pfad absichern: User -> employeeProfile -> schueler
    if ($user->employeeProfile && $user->employeeProfile->schueler) {
        $ersterSchueler = $user->employeeProfile->schueler->first();
        
        if ($ersterSchueler) {
            $schuelerId = $ersterSchueler->id;
            try {
                // Entschlüsselung versuchen
                $schuelerName = decrypt($ersterSchueler->name);
            } catch (\Exception $e) {
                // Falls es schon Klartext ist
                $schuelerName = $ersterSchueler->name;
            }
        }
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

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