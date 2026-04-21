<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Middleware Aliase (behalten)
        $middleware->alias([
            'is_superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
            'has.plan' => \App\Http\Middleware\EnsureHasSubscription::class,
            'avv.accepted' => \App\Http\Middleware\EnsureAvvIsAccepted::class,
        ]);

        // 2. CSRF für Mollie Webhook deaktivieren 
        $middleware->validateCsrfTokens(except: [
            'webhooks/mollie', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();