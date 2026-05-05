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
                // 1. Hole den Employee-Datensatz für den eingeloggten User
                $employee = \App\Models\Employee::where('user_id', $user->id)->first();

                if ($employee) {
                    // 2. Hole den Schüler über die Pivot-Tabelle
                    // Wir nutzen hier direkt die Beziehung aus dem Employee-Model
                    $firstSchueler = $employee->schueler()->first();
                    
                    if ($firstSchueler) {
                        $schuelerId = $firstSchueler->id;
                        
                        // 3. Name auslesen
                        $rawName = $firstSchueler->name;

                        // Wir versuchen zu entschlüsseln, falls es scheitert, nehmen wir den Rohwert
                        try {
                            // Wir prüfen erst, ob es wie ein verschlüsselter Laravel-String aussieht
                            if (is_string($rawName) && strlen($rawName) > 100) {
                                $schuelerName = decrypt($rawName);
                            } else {
                                $schuelerName = $rawName;
                            }
                        } catch (\Exception $e) {
                            // Falls Entschlüsselung fehlschlägt (z.B. falscher Key), nimm den Klartext/Rohwert
                            $schuelerName = $rawName;
                        }
                    } else {
                        $schuelerName = "Mitarbeiter gefunden, aber kein Schüler verknüpft";
                    }
                } else {
                    $schuelerName = "Kein Mitarbeiter-Profil für User " . $user->id;
                }
            } catch (\Throwable $e) {
                // Dieser Catch sorgt dafür, dass der Login NIEMALS mit 500 abbricht
                \Log::error("Schüler-Abfrage Fehler: " . $e->getMessage());
                $schuelerName = "Fehler beim Laden";
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
                    'kinder' => $schuelerListe,
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