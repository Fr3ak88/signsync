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
    $user = \App\Models\User::where('email', $request->email)->first();

    // DEBUG: Wenn der User nicht gefunden wird, liegt es an der E-Mail
    if (!$user) {
        return response()->json(['message' => 'E-Mail nicht gefunden: ' . $request->email], 401);
    }

    // DEBUG: Wenn das Passwort nicht matcht
    if (!\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Passwort falsch für ' . $user->email], 401);
    }

    // Wenn er hier ankommt, ist der Login an sich OK!
    // Jetzt laden wir die Schüler-Daten ganz vorsichtig nach
    $schuelerName = 'Kein Schüler zugewiesen';
    $schuelerId = null;

    if (method_exists($user, 'employeeProfile')) {
        $profile = $user->employeeProfile()->first();
        if ($profile && method_exists($profile, 'schueler')) {
            $s = $profile->schueler()->first();
            if ($s) {
                $schuelerId = $s->id;
                try { $schuelerName = decrypt($s->name); } catch (\Exception $e) { $schuelerName = $s->name; }
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
            'company_name' => $user->company_name ?? 'Firma',
            'schueler_id' => $schuelerId,
            'schueler_name' => $schuelerName,
        ]
    ]);
}
}