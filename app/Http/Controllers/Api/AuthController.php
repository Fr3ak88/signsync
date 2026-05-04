<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'device_name' => 'required', // Name des Handys, z.B. "Samsung S24"
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Die Zugangsdaten sind falsch.'], 401);
        }

        $user = $request->user();
        $schuelerEintrag = $user->schueler()->first();

        // Wir erstellen den Token (den Schlüssel)
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'company_name' => $user->company_name,
                'schueler_id'   => $schuelerEintrag ? $schuelerEintrag->id : null,
                'schueler_name' => $schuelerEintrag ? $schuelerEintrag->name : 'Kein Schüler zugewiesen',
            ]
        ]);
    }
}