<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LegalController extends Controller
{
    /**
     * Zeigt den AVV-Vertrag an.
     */
    public function showAvv()
    {
        // Wir nehmen an, dass der Admin eingeloggt ist und 
        // über die User-Relation auf seine Organisation zugreift.
        $user = Auth::user();
        
        // Falls deine Organisation-Logik anders ist, passe dies an (z.B. $user->organization)
        $organization = $user; 

        return view('legal.avv', compact('organization'));
    }

    /**
     * Speichert die digitale Zustimmung zum AVV.
     */
    public function acceptAvv(Request $request)
    {
        $user = Auth::user();

        // Validierung (Checkbox muss angehakt sein)
        if (!$request->has('acceptCheck')) {
            return back()->with('error', 'Bitte bestätigen Sie die Checkbox, um den AVV zu unterzeichnen.');
        }

        // Speichern der Signatur-Daten in der Datenbank
        // Hinweis: Stelle sicher, dass die Felder in der Migration angelegt wurden!
        $user->update([
            'avv_accepted_at' => Carbon::now(),
            'avv_accepted_ip' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Der Auftragsverarbeitungsvertrag wurde erfolgreich digital unterzeichnet.');
    }
}