<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Mail\EmployeeInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Alle verfügbaren Positionen für das Filter-Menü laden
        $positions = $user->positions;

        // Abfrage starten
        $query = $user->employees();

        // Wenn ein Filter ausgewählt wurde, die Abfrage einschränken
        if ($request->has('position') && $request->position != '') {
            $query->where('position', $request->position);
        }

        $employees = $query->get();

        return view('admin.employees.index', compact('employees', 'positions'));
    }

    public function create()
    {
        // Lädt nur die Positionen deiner Firma
        $positions = \App\Models\Position::where('user_id', auth()->id())->get();
        
        return view('admin.employees.create', compact('positions'));
    }

    public function store(Request $request)
    {
        // 1. Validierung (E-Mail ist nun Pflicht für den Login und muss eindeutig sein)
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'position'   => 'required|string|max:255',
        ]);

        // 2. Den User-Account (Login) erstellen
        // Wir vergeben ein zufälliges Passwort, da der User über den Link ein eigenes setzt
        $user = User::create([
            'name'     => $data['first_name'] . ' ' . $data['last_name'],
            'email'    => $data['email'],
            'password' => Hash::make(Str::random(32)),
            // Falls du ein Feld 'company' in der User-Tabelle hast:
            // 'company' => Auth::user()->company, 
        ]);

        // 3. Den Mitarbeiter-Datensatz erstellen und mit dem neuen User verknüpfen
        $employee = new Employee();
        $employee->user_id    = $user->id; // Verknüpfung zur Users-Tabelle
        $employee->admin_id   = Auth::id(); // Zuordnung zum Admin/Firma
        $employee->first_name = $data['first_name'];
        $employee->last_name  = $data['last_name'];
        $employee->email      = $data['email'];
        $employee->position   = $data['position'];
        $employee->save();

        // Alternativ über die Beziehung, falls eingerichtet:
        // Auth::user()->employees()->create(array_merge($data, ['user_id' => $user->id]));

        // 4. Passwort-Token generieren und Einladungs-Link erstellen
        $token = Password::createToken($user);
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        // 5. E-Mail versenden
        try {
            Mail::to($user->email)->send(new EmployeeInvitationMail($user->name, $url));
            $message = 'Mitarbeiter erfolgreich angelegt und Einladung versendet!';
        } catch (\Exception $e) {
            $message = 'Mitarbeiter angelegt, aber E-Mail-Versand fehlgeschlagen. Bitte SMTP-Einstellungen prüfen.';
        }

        return redirect('/admin/employees')->with('success', $message);
    }

    public function edit($id)
    {
        $employee = Auth::user()->employees()->findOrFail($id);
        
        // Nur die Positionen laden, die zu DIESER Firma gehören
        $positions = Auth::user()->positions; 

        return view('admin.employees.edit', compact('employee', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $employee = Auth::user()->employees()->findOrFail($id);
        
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'position'   => 'required|string|max:255',
        ]);

        $employee->update($data);

        // Optional: Namen auch im User-Account aktualisieren
        if ($employee->user) {
            $employee->user->update([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email']
            ]);
        }

        return redirect('/admin/employees')->with('success', 'Mitarbeiter wurde aktualisiert.');
    }

    public function destroy($id)
    {
        $employee = Auth::user()->employees()->findOrFail($id);
        
        // Wenn der Mitarbeiter gelöscht wird, entscheiden: 
        // Soll der Login-Account auch gelöscht werden?
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return redirect('/admin/employees')->with('success', 'Mitarbeiter und zugehöriger Login gelöscht.');
    }
}