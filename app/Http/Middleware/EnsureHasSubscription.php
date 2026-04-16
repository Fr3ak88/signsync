<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Nur Admins müssen ein Abo wählen (Mitarbeiter hängen am Admin-Abo)
        // 2. Wir prüfen, ob bereits ein Paket-Name hinterlegt ist
        // 3. Wir lassen Anfragen zur Preisseite und zum Logout durch, sonst gibt es einen Endlos-Loop
        if ($user && $user->role === 'admin' && empty($user->plan_name)) {
            if (!$request->routeIs('plans.index') && !$request->routeIs('plans.store') && !$request->routeIs('logout')) {
                return redirect()->route('plans.index')->with('info', 'Bitte wählen Sie zuerst ein passendes Paket aus.');
            }
        }

        return $next($request);
    }
}