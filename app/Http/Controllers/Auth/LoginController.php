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