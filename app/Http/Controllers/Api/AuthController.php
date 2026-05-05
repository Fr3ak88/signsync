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

            // 3. Sicherer Zugriff auf die Kette: User -> employeeProfile -> schueler
            if ($user->employeeProfile) {
                $employee = $user->employeeProfile;
                
                if (method_exists($employee, 'schueler')) {
                    $s = $employee->schueler()->first();
                    
                    if ($s) {
                        $schuelerId = $s->id;
                        try {
                            // Versuch der Entschlüsselung
                            $schuelerName = decrypt($s->name);
                        } catch (DecryptException $e) {
                            // Falls Payload ungültig (dein Fehler von 06:40), 
                            // zeige den rohen Wert oder einen Hinweis, statt abzustürzen
                            $schuelerName = "Verschlüsselungsfehler (Daten passen nicht zum Key)";
                            
                            // Optional: Falls du den verschlüsselten String sehen willst, nutze:
                            // $schuelerName = $s->name; 
                        }
                    }
                }
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