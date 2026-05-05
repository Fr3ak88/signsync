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
    try {
        // Validierung
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
        }

        $schuelerName = 'Kein Schüler zugewiesen';
        $schuelerId = null;

        // Wir prüfen ganz vorsichtig die Kette
        if ($user->employeeProfile) {
            $employee = $user->employeeProfile;
            
            // Falls du 'schueler' als Beziehung im Employee-Model hast
            if (method_exists($employee, 'schueler')) {
                $s = $employee->schueler()->first();
                if ($s) {
                    $schuelerId = $s->id;
                    try {
                        $schuelerName = decrypt($s->name);
                    } catch (\Exception $e) {
                        $schuelerName = $s->name;
                    }
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
                'schueler_id' => $schuelerId,
                'schueler_name' => $schuelerName,
            ]
        ]);

    } catch (\Throwable $e) {
        // Throwable fängt wirklich ALLES ab (auch Error-Objekte)
        return response()->json([
            'error_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}