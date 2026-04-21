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
        $user = Auth::user();

        // Logik: Ein Admin sieht seine eigenen Schüler. 
        // Ein Mitarbeiter sieht die Schüler des Admins, der ihn angelegt hat.
        $adminId = ($user->role === 'admin') ? $user->id : $user->admin_id;

        $schueler = Schueler::where('admin_id', $adminId)
                            ->orderBy('name', 'asc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $schueler
        ]);
    }
}