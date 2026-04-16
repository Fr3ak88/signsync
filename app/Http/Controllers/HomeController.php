<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Position;
use App\Models\Zeiteintrag;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // 1. WEICHE: Super-Admin (bleibt bei der Umleitung)
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.index'); 
        }

        // Initialisierung der Variablen für die View (verhindert "Undefined Variable" Fehler)
        $employeeCount = 0;
        $positionCount = 0;
        $eintraege = collect(); // Erstellt eine leere Collection

        // 2. LOGIK: Admin (Firmenleiter)
        if ($user->role === 'admin') {
    // count() auf der Relationship-Methode ist am schnellsten (SQL-Ebene)
    $employeeCount = $user->employees()->count(); 
    
    // Das Gleiche für Positionen (falls die Methode im Model existiert)
    $positionCount = $user->positions()->count();

    $eintraege = Zeiteintrag::whereHas('user', function($q) use ($user) {
        $q->where('admin_id', $user->id);
    })->get();
}
        // 3. LOGIK: Mitarbeiter
        else {
            $eintraege = Zeiteintrag::where('user_id', $user->id)
                                ->with('schueler') 
                                ->latest()
                                ->take(5)
                                ->get();
        }

        // WICHTIG: Wir geben IMMER die 'home' view zurück, 
        // da dein Template dort die Unterscheidung per @if(role === 'admin') macht.
        return view('home', compact('employeeCount', 'positionCount', 'eintraege'));
    }
}