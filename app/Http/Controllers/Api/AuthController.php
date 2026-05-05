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

            try {
                if ($user->employeeProfile) {
                    $employee = $user->employeeProfile;
                    
                    // Wir holen den ersten Schüler, falls vorhanden
                    $s = $employee->schueler()->first();
                    
                    if ($s) {
                        $schuelerId = $s->id;
                        
                        // Wir prüfen MANUELL, ob der Name verschlüsselt aussieht 
                        // (Laravel-Verschlüsselungen sind immer lange Strings mit JSON-Inhalt)
                        if (str_contains($s->name, '{"iv":')) {
                            try {
                                $schuelerName = decrypt($s->name);
                            } catch (\Exception $e) {
                                $schuelerName = "Verschlüsselungs-Fehler";
                            }
                        } else {
                            // Wenn es kein Verschlüsselungs-Format ist (dein Klartext-Test),
                            // nehmen wir den Namen einfach direkt.
                            $schuelerName = $s->name;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Wenn IRGENDWAS in der Kette oben schiefgeht, loggen wir es nur,
                // aber wir lassen den Login NICHT sterben!
                \Log::error("Schüler-Login-Fehler ignoriert: " . $e->getMessage());
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