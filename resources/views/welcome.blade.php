<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SignSync - Digitale Zeiterfassung</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        /* BACKUP-STYLING (falls Vite blockiert) */
        body { font-family: 'Instrument Sans', sans-serif; background-color: #f8fafc; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 10%; background: white; border-bottom: 1px solid #e2e8f0; }
        .logo { font-weight: bold; font-size: 1.25rem; color: #1e293b; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #64748b; font-size: 0.9rem; }
        .main-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 900px; display: flex; overflow: hidden; }
        .card-left { flex: 1; padding: 3rem; }
        .card-right { width: 300px; background: #f1f5f9; padding: 3rem; display: flex; flex-direction: column; justify-content: center; border-left: 1px solid #e2e8f0; }
        .btn-primary { background: #4f46e5; color: white; padding: 0.75rem; border-radius: 0.375rem; text-align: center; text-decoration: none; font-weight: bold; }
        .check-svg { width: 18px; height: 18px; color: #22c55e; margin-right: 10px; flex-shrink: 0; }
        .feature-item { display: flex; align-items: center; margin-bottom: 1rem; font-size: 0.95rem; }
        h1 { font-size: 2rem; color: #1e293b; margin-bottom: 1rem; }
        p { color: #64748b; line-height: 1.6; }
        @media (max-width: 768px) { .card { flex-direction: column; } .card-right { width: auto; border-left: none; border-top: 1px solid #e2e8f0; } }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">SignSync</div>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Registrieren</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="main-container">
        <main class="card">
            <div class="card-left">
                <h1>Zeiterfassung für Schulbegleitungen.</h1>
                <p>Schluss mit Zettelwirtschaft. Erfassen Sie Einsatzzeiten digital, lassen Sie per eSignatur unterschreiben und exportieren Sie fertige Leistungsnachweise.</p>
                
                <div style="margin-top: 2rem;">
                    <div class="feature-item">
                        <svg class="check-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        <span>Rechtssichere <strong>eSignatur</strong> vor Ort.</span>
                    </div>
                    <div class="feature-item">
                        <svg class="check-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        <span>Automatisierte <strong>PDF-Erstellung</strong>.</span>
                    </div>
                </div>
            </div>

            <div class="card-right">
                <a href="{{ route('register') }}" class="btn-primary">Jetzt starten</a>
            </div>
        </main>
    </div>

    <footer style="text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.8rem;">
        &copy; {{ date('Y') }} SignSync – Effiziente Begleitung, digitale Lösung.
    </footer>

</body>
</html>