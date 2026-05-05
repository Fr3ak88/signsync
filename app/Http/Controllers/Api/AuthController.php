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

            // Check ob User existiert und Passwort stimmt
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Login fehlgeschlagen.'], 401);
            }

            // 3. Alle zugeordneten Schüler laden
            $kinderListe = [];
            
            try {
                // Wir nutzen die Beziehung über das employeeProfile
                if ($user->employeeProfile) {
                    $schuelerEintraege = $user->employeeProfile->schueler()->get();
                    
                    foreach ($schuelerEintraege as $s) {
                        $kinderListe[] = [
                            'id' => $s->id,
                            'name' => $s->name,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                \Log::error("Schüler-Liste konnte nicht geladen werden: " . $e->getMessage());
            }

            $companyName = 'SignSync'; // Standard-Fallback

            if ($user->employeeProfile) {
                // Wir suchen den Admin/Arbeitgeber, dem dieser Mitarbeiter zugeordnet ist
                // Ich nehme an, die Spalte in 'employees' heißt 'admin_id'
                $admin = \App\Models\User::find($user->employeeProfile->admin_id);
                
                if ($admin && $admin->company_name) {
                    $companyName = $admin->company_name;
                }
            }


            // 4. Token erstellen
            $token = $user->createToken($request->device_name)->plainTextToken;

            // 5. Antwort mit der Liste aller Kinder
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'kinder' => $kinderListe,
                    'company_name' => $companyName,,
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