<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zeiteintrag;
use App\Models\Schueler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // Hier sicherstellen, dass nur eingeloggte Admins Zugriff haben
        $this->middleware(['auth', 'is_superadmin']); 
    }

    /**
     * Dashboard-Übersicht
     */
    public function index()
    {
        return view('superadmin.dashboard', [
            'total_firmen' => User::where('role', 'admin')->count(),
            'total_eintraege' => Zeiteintrag::count(),
            'neue_user_heute' => User::whereDate('created_at', today())->count(),
            'firmen_liste' => User::where('role', 'admin')->latest()->take(5)->get(),
        ]);
    }

    /**
     * Liste ALLER User
     */
    public function users()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('superadmin.users.index', compact('users'));
    }

    /**
     * User oder Unternehmen bearbeiten
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $firmenListe = User::whereNotNull('company')
                           ->where('company', '!=', '')
                           ->distinct()
                           ->pluck('company')
                           ->sort();

        return view('superadmin.users.edit', compact('user', 'firmenListe'));
    }

    /**
     * Änderungen speichern (Name, E-Mail, Rolle, Firma)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'company' => 'nullable|string|max:255',
            'role'    => 'required|in:admin,employee,superadmin'
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'company' => $request->company,
            'role'    => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User ' . $user->name . ' wurde erfolgreich aktualisiert.');
    }

    /**
     * NEU: Abo-Plan manuell für einen User (Unternehmen) setzen
     */
    public function updatePlan(Request $request, User $user)
    {
        $request->validate([
            'plan' => 'required|in:starter,team,pro,none',
        ]);

        // Definition der Mitarbeiter-Limits pro Paket
        $limits = [
            'starter' => 5,
            'team'    => 20,
            'pro'     => 1000,
            'none'    => 0
        ];

        if ($request->plan === 'none') {
            $user->update([
                'plan_name' => null,
                'has_active_subscription' => false,
                'max_employees' => $limits['none']
            ]);
        } else {
            $user->update([
                'plan_name' => $request->plan,
                'has_active_subscription' => true,
                'max_employees' => $limits[$request->plan]
            ]);
        }

        return back()->with('success', "Plan für {$user->name} erfolgreich auf " . ucfirst($request->plan) . " gesetzt.");
    }

    /**
     * Bestehende Firmenliste
     */
    public function firmen()
    {
        $firmen = User::where('role', 'admin')->latest()->get();
        return view('superadmin.firmen', compact('firmen'));
    }

    /**
     * Löschfunktion (DSGVO-konform)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == 1) {
            return back()->with('error', 'Der Haupt-Administrator ist geschützt.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Sie können sich nicht selbst löschen.');
        }

        Zeiteintrag::where('user_id', $user->id)->delete();
        Schueler::where('admin_id', $user->id)->delete();

        $user->delete();

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User und alle zugehörigen Daten wurden gelöscht.');
    }

    /**
     * Statistiken
     */
    public function stats()
    {
        $statsData = Zeiteintrag::selectRaw('count(*) as count, DATE_FORMAT(created_at, "%M") as month, MIN(created_at) as sort_date')
            ->groupBy('month')
            ->orderBy('sort_date', 'asc')
            ->get();

        return view('superadmin.stats', compact('statsData'));
    }

    public function resendInvitation(User $user)
    {
        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);

        return back()->with('success', 'Ein neuer Einladungs-Link wurde an ' . $user->email . ' gesendet.');
    }
}