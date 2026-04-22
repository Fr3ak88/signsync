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
        // Der Admin sieht weiterhin alle Schüler seiner Firma
        $schueler = Schueler::where('admin_id', $user->id)->get();
    } else {
        // Der Mitarbeiter sieht NUR die Schüler, die in 'employee_schueler' verknüpft sind
        $schueler = $user->zugewieseneSchueler()->get();
    }

    return response()->json([
        'success' => true,
        'data' => $schueler
    ]);
    }
}