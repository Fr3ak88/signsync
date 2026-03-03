@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark">SignSync Dashboard: {{ Auth::user()->company }}</h1>
            <p class="text-muted text-lg">
                Willkommen zurück, {{ Auth::user()->name }}. 
                @if(Auth::user()->role === 'admin')
                    Hier verwalten Sie Ihre Schulbegleitungen und Einsätze.
                @else
                    Hier können Sie Ihre Arbeitszeiten erfassen und einsehen.
                @endif
            </p>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Mitarbeiter</h5>
                        <p class="card-text display-6 fw-bold">{{ $employeeCount ?? 0 }}</p> 
                        <a href="/admin/employees" class="btn btn-light btn-sm text-primary fw-bold">
                            Verwalten →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Leistungsnachweise</h5>
                        <p class="card-text display-6 fw-bold">Offen</p>
                        <a href="#" class="btn btn-dark btn-sm">Ansehen</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Exports</h5>
                        <p class="card-text display-6 fw-bold">PDFs</p>
                        <a href="#" class="btn btn-light btn-sm text-success fw-bold">Archiv</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 fw-bold">
                        Schnellzugriff für {{ Auth::user()->company }}
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="/admin/employees" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3">
                                <span><i class="bi bi-people me-2 text-primary"></i> Mitarbeiter-Verzeichnis</span>
                                <span class="badge bg-light text-primary rounded-pill border">→</span>
                            </a>
                            <a href="/admin/positions" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3">
                                <span><i class="bi bi-tags me-2 text-primary"></i> Eigene Positionen verwalten</span>
                                <span class="badge bg-primary rounded-pill">{{ $positionCount ?? 0 }} konfiguriert</span>
                            </a>
                            <a href="/zeiteintraege" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3">
                                <span><i class="bi bi-clock-history me-2 text-primary"></i> Alle Zeiteinträge prüfen</span>
                                <span class="badge bg-light text-secondary rounded-pill border">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Hilfe & Support</h6>
                        <hr>
                        <p class="small mb-2">Angemeldet als Admin: <strong>{{ Auth::user()->email }}</strong></p>
                        <p class="small text-muted">Bei Fragen kontaktieren Sie bitte unseren Support.</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="fw-bold"><i class="bi bi-calendar-check me-2"></i>Zeiteinträge</h2>
                                <p class="lead mb-0">Erfassen Sie hier Ihre heutigen Einsätze für die Schulbegleitung.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="/zeiteintraege/create" class="btn btn-light btn-lg fw-bold text-success shadow-sm px-4">
                                    <i class="bi bi-plus-circle me-1"></i> Einsatz eintragen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>Meine letzten Einträge</span>
                        <a href="/zeiteintraege" class="btn btn-sm btn-outline-primary">Alle ansehen</a>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-text display-1 text-light"></i>
                        <p class="text-muted mt-3">Keine Einträge für den aktuellen Zeitraum vorhanden.</p>
                        <a href="/zeiteintraege/create" class="btn btn-sm btn-link text-decoration-none">Ersten Eintrag erstellen</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold py-3">Mein Profil</div>
                    <div class="card-body">
                        <p class="small text-muted mb-1">Zugeordnete Position:</p>
                        <p class="fw-bold text-primary mb-3">
                            {{ Auth::user()->employee->position ?? 'Standard Nutzer' }}
                        </p>
                        <hr>
                        <div class="p-3 bg-light rounded small text-muted">
                            <i class="bi bi-shield-check text-success me-1"></i> 
                            Datenübertragung gesichert.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection