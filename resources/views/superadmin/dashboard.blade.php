@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="badge bg-danger mb-2">Super-Admin Bereich</div>
            <h1 class="fw-bold text-dark">Betreiber-Zentrale (Master-Dashboard)</h1>
            <p class="text-muted">Systemweite Übersicht über alle Mandanten und Aktivitäten.</p>
        </div>
    </div>

    {{-- Statistik-Karten --}}
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body text-center">
                    <h6 class="opacity-75">Registrierte Firmen</h6>
                    <p class="display-5 fw-bold mb-0">{{ $total_firmen }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Gesamt-Zeiteinträge</h6>
                    <p class="display-5 fw-bold mb-0 text-primary">{{ $total_eintraege }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Neue User (Heute)</h6>
                    <p class="display-5 fw-bold mb-0 text-success">{{ $neue_user_heute }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body text-center">
                    <h6 class="opacity-75">System-Status</h6>
                    <p class="fs-4 fw-bold mb-0">DSGVO Konform</p>
                    <small><i class="bi bi-shield-lock-fill"></i> Verschlüsselung aktiv</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="bi bi-building me-2 text-primary"></i>Neueste Firmen-Registrierungen
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Firma</th>
                                    <th>Admin</th>
                                    <th>Datum</th>
                                    <th class="text-end pe-4">Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($firmen_liste ?? [] as $firma)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $firma->company ?? 'Kein Name' }}</td>
                                    <td>{{ $firma->email }}</td>
                                    <td>{{ $firma->created_at->format('d.m.Y') }}</td>
                                    <td class="text-end pe-4">
                                        {{-- Link zum Editieren des Users/Firma --}}
                                        <a href="{{ route('superadmin.users.edit', $firma->id) }}" class="btn btn-sm btn-outline-dark">Bearbeiten</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Noch keine Firmen registriert.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Admin-Werkzeuge</h6>
                    <div class="d-grid gap-2">
                        {{-- NEU: Link zur kompletten User-Übersicht --}}
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-dark text-start shadow-sm">
                            <i class="bi bi-person-badge-fill me-2"></i> Alle User & Zuweisungen
                        </a>
                        
                        <a href="/superadmin/firmen" class="btn btn-primary text-start shadow-sm">
                            <i class="bi bi-building me-2"></i> Firmen-Übersicht (Liste)
                        </a>
                        
                        <a href="/superadmin/stats" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-graph-up me-2"></i> System-Statistiken
                        </a>
                        <hr>
                        <p class="small text-muted">
                            <i class="bi bi-info-circle me-1"></i> 
                            Nutzen Sie die <strong>User-Zuweisung</strong>, um neu registrierte Personen einer Firma zuzuordnen oder Rollen zu ändern.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection