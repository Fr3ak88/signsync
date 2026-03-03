<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Wichtig: Model importieren
use App\Models\Position; // Wichtig: Model importieren
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

        // Statistiken laden: Nur die Daten der eigenen Firma zählen
        $employeeCount = $user->employees()->count();
        $positionCount = $user->positions()->count();

        // Wir geben die Variablen an die View weiter
        return view('home', compact('employeeCount', 'positionCount'));
    }
}