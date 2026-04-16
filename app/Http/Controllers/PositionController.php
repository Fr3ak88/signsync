<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index()
    {
        // Lädt alle Positionen, die DIESER User (Firma) angelegt hat
        $positions = Auth::user()->positions;
        return view('admin.positions.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Erstellt die Position direkt über die Beziehung zum User
        Auth::user()->positions()->create([
            'name' => $request->name
        ]);

        return redirect('/admin/positions')->with('success', 'Position erfolgreich hinzugefügt!');
    }

    public function destroy($id)
    {
        // Sicherstellen, dass man nur eigene Positionen löschen kann
        $position = Auth::user()->positions()->findOrFail($id);
        $position->delete();

        return redirect('/admin/positions')->with('success', 'Position wurde gelöscht.');
    }
    public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (auth()->user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Keine Berechtigung für diesen Bereich.');
        }
        return $next($request);
    });
}
}