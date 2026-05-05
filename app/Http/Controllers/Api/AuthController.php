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

            // 4. Token erstellen
            $token = $user->createToken($request->device_name)->plainTextToken;

            // --- FIRMENNAME SICHER LADEN ---
            $companyName = 'SignSync'; // Standardwert

            try {
                // 1. Prüfen, ob der User ein Employee-Profil hat
                if ($user->employeeProfile) {
                    
                    // 2. Wir versuchen den Admin über die admin_id im Profil zu finden
                    // WICHTIG: Prüfe in deiner DB, ob die Spalte in 'employees' wirklich 'admin_id' heißt!
                    $adminId = $user->employeeProfile->admin_id;
                    
                    if ($adminId) {
                        $admin = \App\Models\User::find($adminId);
                        if ($admin) {
                            // 3. Firmennamen vom Admin nehmen (oder dessen Namen, falls company_name leer ist)
                            $companyName = $admin->company ?? $admin->name ?? 'SignSync';
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Falls hier was schiefgeht, loggen wir es, aber lassen den Login nicht sterben
                \Log::error("Firmenname konnte nicht geladen werden: " . $e->getMessage());
            }

            // 5. Antwort mit der Liste aller Kinder
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'kinder' => $kinderListe,
                    'company_name' => $companyName,
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