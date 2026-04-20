<?php

namespace App\Http\Controllers;

use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchuelerController extends Controller
{
    /**
     * Nur eingeloggte Admins dürfen hier zugreifen.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste aller Schüler der eigenen Firma anzeigen.
     */
    public function index()
    {
        $schueler = Schueler::where('admin_id', Auth::id())
                            ->orderBy('name', 'asc')
                            ->get();

        return view('admin.schueler.index', compact('schueler'));
    }

    /**
     * Das Formular zum Erstellen anzeigen.
     */
    public function create()
    {
        return view('admin.schueler.create');
    }

    /**
     * Den neuen Schüler in die Datenbank speichern.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'birth_date'  => 'nullable|date',
            'school_name' => 'nullable|string|max:255',
        ]);

        Schueler::create([
            'name'        => $request->name,
            'birth_date'  => $request->birth_date,
            'school_name' => $request->school_name,
            'admin_id'    => Auth::id(),
        ]);

        return redirect()->route('admin.schueler.index')
                         ->with('success', 'Schüler wurde erfolgreich angelegt.');
    }

    /**
     * NEU: Das Formular zum Bearbeiten anzeigen.
     */
    public function edit($id)
    {
        // Sicherstellen, dass der Admin nur Schüler der eigenen Firma bearbeiten kann
        $schueler = Schueler::where('admin_id', Auth::id())->findOrFail($id);
        
        return view('admin.schueler.edit', compact('schueler'));
    }

    /**
     * NEU: Die Änderungen in der Datenbank aktualisieren.
     */
    public function update(Request $request, $id)
    {
        $schueler = Schueler::where('admin_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'birth_date'  => 'nullable|date',
            'school_name' => 'nullable|string|max:255',
        ]);

        $schueler->update([
            'name'        => $request->name,
            'birth_date'  => $request->birth_date,
            'school_name' => $request->school_name,
        ]);

        return redirect()->route('admin.schueler.index')
                         ->with('success', 'Schülerdaten wurden erfolgreich aktualisiert.');
    }

    /**
     * Einen Schüler löschen.
     */
    public function destroy($id)
    {
        $schueler = Schueler::where('admin_id', Auth::id())->findOrFail($id);
        $schueler->delete();

        return redirect()->route('admin.schueler.index')
                         ->with('success', 'Schüler wurde gelöscht.');
    }

    public function schule()
    {
    return $this->belongsTo(Schule::class, 'schule_id');
    }
}