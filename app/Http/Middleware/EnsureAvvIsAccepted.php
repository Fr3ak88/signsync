<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAvvIsAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $user = auth()->user();

    // Logik: 
    // 1. User ist eingeloggt
    // 2. User ist Admin
    // 3. AVV ist NICHT akzeptiert
    // 4. User versucht NICHT bereits die AVV-Seite oder Logout aufzurufen
    if ($user && $user->role === 'admin' && !$user->avv_accepted_at) {
        if (!$request->routeIs('admin.avv.*') && !$request->routeIs('logout')) {
            return redirect()->route('admin.avv.show')
                ->with('error', 'Bitte unterzeichnen Sie erst den AVV, um fortzufahren.');
        }
    }

    return $next($request);
}
}
