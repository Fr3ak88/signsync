<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ];

        // Nur Admins dürfen Firmendaten ändern
        if ($user->role === 'admin') {
            $rules = array_merge($rules, [
                'company' => 'nullable|string|max:255',
                'street' => 'nullable|string|max:255',
                'house_number' => 'nullable|string|max:20',
                'zip_code' => 'nullable|string|max:10',
                'city' => 'nullable|string|max:255',
            ]);
        }

        $data = $request->validate($rules);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profil erfolgreich aktualisiert.');
    }
}