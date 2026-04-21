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

    // Wenn Admin: Zeige alle Kinder der Firma
    if ($user->role === 'admin') {
        $schueler = Schueler::where('admin_id', $user->id)->get();
    } else {
        // Wenn Mitarbeiter: Zeige nur Kinder, die ihm zugeordnet sind
        $schueler = Schueler::where('user_id', $user->id)->get();
    }

    return response()->json([
        'success' => true,
        'data' => $schueler
    ]);
}
}