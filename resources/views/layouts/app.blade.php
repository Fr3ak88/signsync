<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SignSync - Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: 'Instrument Sans', sans-serif; 
            background-color: #f8fafc; 
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
        }
        
        #app { display: flex; flex-direction: column; flex: 1; }
        
        .navbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 1rem 10%; 
            background: white; 
            border-bottom: 1px solid #e2e8f0; 
        }
        
        .logo { font-weight: bold; font-size: 1.25rem; color: #1e293b; text-decoration: none; }
        
        .card { 
            border: none; 
            border-radius: 0.75rem; 
            transition: box-shadow 0.2s, background-color 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
            background-color: #f8f9fa;
        }
        
        footer {
            text-align: center; 
            padding: 2rem; 
            color: #94a3b8; 
            font-size: 0.8rem; 
            border-top: 1px solid #f1f5f9; 
            background: white;
            margin-top: auto;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 0.9rem;
        }

        .nav-links a.active {
            color: #1e293b !important;
            font-weight: 600;
        }

        .breadcrumb { margin-bottom: 0; }
        .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar shadow-sm">
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="logo">SignSync</a>
        <div class="nav-links d-flex align-items-center">
            <a href="{{ route('plans.index') }}" class="text-muted me-3">Preise</a>

            @auth
                <span class="text-muted me-3 small">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                </span>
                
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="color: #ef4444; font-weight: bold;" class="small">
                   Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @else
                <a href="{{ route('login') }}" class="text-muted me-3">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-muted border px-2 py-1 rounded">Registrieren</a>
                @endif
            @endauth
        </div>
    </nav>

    <main class="py-4">
        @auth
            <div class="container mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white p-2 px-3 rounded shadow-sm border small">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        @foreach(Request::segments() as $segment)
                            @if(!in_array($segment, ['admin', 'dashboard']))
                                <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">
                                    @php
                                        $translations = [
                                            'employees' => 'Mitarbeiterverwaltung',
                                            'positions' => 'Einsatzbereiche',
                                            'create'    => 'Neu anlegen',
                                            'edit'      => 'Bearbeiten',
                                            'profile'   => 'Profil'
                                        ];
                                        echo $translations[$segment] ?? ucfirst(str_replace('-', ' ', $segment));
                                    @endphp
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
        @endauth

        {{-- Fehlermeldungen --}}
        @if(session('error'))
            <div class="container mb-3">
                <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('success'))
            <div class="container mb-3">
                <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div>
            &copy; {{ date('Y') }} <strong>SignSync</strong> – Effiziente Begleitung, digitale Lösung.
        </div>
        <div style="margin-top: 0.5rem;">
            <a href="{{ route('impressum') }}" style="color: #94a3b8; margin: 0 10px; text-decoration: none;">Impressum</a>
            |
            <a href="{{ route('datenschutz') }}" style="color: #94a3b8; margin: 0 10px; text-decoration: none;">Datenschutz</a>
            |
            <a href="#" style="color: #94a3b8; margin: 0 10px; text-decoration: none;">AGB</a>
        </div>
        <div>
            Alle Preise zzgl. MwSt.
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>