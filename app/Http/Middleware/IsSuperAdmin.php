<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // 1. Ist der User überhaupt eingeloggt?
    if (!auth()->check()) {
        return redirect('/login');
    }

    // 2. Hat er die Rolle superadmin?
    if (auth()->user()->role === 'superadmin') {
        return $next($request);
    }

    return redirect('/dashboard')->with('error', 'Kein Zutritt zum Maschinenraum!');
    }
}
