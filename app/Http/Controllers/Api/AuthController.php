<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

            // 3. Schüler-Liste vorbereiten
            $schuelerListe = [];
            
            try {
                // Wir nutzen die Beziehung über das employeeProfile
                if ($user->employeeProfile) {
                    // Da dein Model jetzt automatisch entschlüsselt, 
                    // reicht ein einfaches get()
                    $kinder = $user->employeeProfile->schueler()->get();
                    
                    foreach ($kinder as $kind) {
                        $schuelerListe[] = [
                            'id' => $kind->id,
                            'name' => $kind->name, // Automatisch Klartext durch Model-Casting
                        ];
                    }
                }
            } catch (\Throwable $e) {
                \Log::error("Fehler beim Laden der Schüler-Liste: " . $e->getMessage());
                // Wir lassen die Liste leer, damit der Login trotzdem klappt
            }

            // 4. Token erstellen
            $token = $user->createToken($request->device_name)->plainTextToken;

            // 5. Erfolgreiche Antwort mit der Liste "kinder"
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'kinder' => $schuelerListe, // Hier sind nun alle zugeordneten Kinder drin
                ]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}