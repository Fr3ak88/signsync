<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchuelerController extends Controller
{
    public function index(Request $request)
{
    $user = $request->user();

    if ($user->role === 'admin') {
        // Admin sieht alle Schüler seiner Firma
        $schueler = Schueler::where('admin_id', $user->id)->get();
    } else {
        // Mitarbeiter sieht nur Schüler, die ihm zugeordnet sind
        // Ersetze 'employee_id' durch den echten Namen deiner Spalte (z.B. 'betreuer_id')
        $schueler = Schueler::where('employee_id', $user->id)->get(); 
    }

    return response()->json([
        'success' => true,
        'data' => $schueler
    ]);
}
}