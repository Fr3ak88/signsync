<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use Illuminate\Http\Request;

class ZeiteintragController extends Controller
{
    public function create()
    {
        $schueler = Schueler::all();
        return view('zeiteintraege.create', compact('schueler'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'schueler_id' => 'required|exists:schuelers,id',
        'start_zeit' => 'required|date',
        'ende_zeit' => 'required|date|after:start_zeit'
    ]);
    
    // TODO: Datenbank speichern
    return redirect()->back()->with('success', 'Zeitraum gespeichert!');
	}
}
