<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException; // Wichtig für den Catch
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        try {
            // 1. Validierung
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            // 2. User laden
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
            }

            $schuelerName = 'Kein Schüler zugewiesen';
            $schuelerId = null;

            // 1. Wir holen den Mitarbeiter-Eintrag (ID 1) über die user_id (ID 3)
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();

            if ($employee) {
                // 2. Jetzt holen wir den Schüler über die Beziehung, 
                // die auf die employee_id (1) in der Pivot-Tabelle achtet.
                $s = $employee->schueler()->first();
                
                if ($s) {
                    $schuelerId = $s->id;
                    
                    // 3. Name auslesen (mit Entschlüsselungs-Check)
                    try {
                        if (str_starts_with($s->name, 'eyJpdiI6')) {
                            $schuelerName = decrypt($s->name);
                        } else {
                            $schuelerName = $s->name;
                        }
                    } catch (\Exception $e) {
                        $schuelerName = $s->name; // Fallback auf Rohdaten
                    }
                } else {
                    // Debug-Meldung, falls die Pivot-Tabelle leer ist
                    $schuelerName = "Mitarbeiter gefunden (ID: {$employee->id}), aber kein Schüler verknüpft.";
                }
            } else {
                // Debug-Meldung, falls der User kein Mitarbeiter-Profil hat
                $schuelerName = "Kein Mitarbeiter-Profil für User-ID {$user->id}";
            }

            // 4. Token erstellen
            $token = $user->createToken($request->device_name)->plainTextToken;

            // 5. Erfolgreiche Antwort
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
            // Fängt alle anderen kritischen Fehler ab
            return response()->json([
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}