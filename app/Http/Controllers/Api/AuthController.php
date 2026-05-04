<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeProfile;
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

    // User suchen
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
    }

    $schuelerName = 'Kein Schüler zugewiesen';
    $schuelerId = null;

    // Wir laden das Profil manuell nach, um Fehler zu vermeiden
    try {
        if (method_exists($user, 'employeeProfile')) {
            $profile = $user->employeeProfile()->first();
            
            if ($profile && method_exists($profile, 'schueler')) {
                $schueler = $profile->schueler()->first();
                if ($schueler) {
                    $schuelerId = $schueler->id;
                    try {
                        $schuelerName = decrypt($schueler->name);
                    } catch (\Exception $e) {
                        $schuelerName = $schueler->name;
                    }
                }
            }
        }
    } catch (\Exception $e) {
        // Falls hier etwas schiefgeht, loggen wir den Fehler, aber lassen den Login durch
        \Log::error("Fehler beim Laden des Schuelers: " . $e->getMessage());
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