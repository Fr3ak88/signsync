<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Zeigt die Liste aller Mitarbeiter der Firma
    public function index()
    {
        $employees = User::where('company_id', Auth::id())->get();
        return view('admin.employees.index', compact('employees'));
    }

    // Speichert einen neuen Mitarbeiter
    public function storeEmployee(Request $request)
    {
        // 1. Validierung
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8',
        ]);

        // 2. Mitarbeiter erstellen
        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => $request->first_name . ' ' . $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'company_id' => Auth::id(), // Hier wird der Mitarbeiter der Firma zugeordnet
            'role'       => 'employee',
        ]);

        return redirect()->back()->with('success', 'Mitarbeiter erfolgreich angelegt!');
    }
}