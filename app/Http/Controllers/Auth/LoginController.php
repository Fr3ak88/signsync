<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Diese Methode ersetzt die statische Variable $redirectTo
     * und ermöglicht eine dynamische Weiterleitung basierend auf der Rolle.
     */
    protected function redirectTo()
    {
        if (Auth::user()->role === 'superadmin') {
            return route('superadmin.index');
        }

        return '/dashboard';
    }

    protected function authenticated($request, $user)
    {
    // Wenn die Anfrage JSON erwartet (wie von unserer Flutter App)
    if ($request->wantsJson()) {
        return response()->json([
            'token' => $user->createToken('mobile_app')->plainTextToken,
            'user' => [
                'name' => $user->name,
                // Hier wird der Firmenname aus der Relation geladen
                'company_name' => $user->company ? $user->company->name : 'SignSync',
            ]
        ]);
    }

    // Standard-Verhalten für Web-Browser bleibt gleich
    return redirect()->intended($this->redirectPath());
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // In Laravel 11/UI-Kit: logout muss für auth zugänglich sein
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
