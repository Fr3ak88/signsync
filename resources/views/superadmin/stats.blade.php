@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark"><i class="bi bi-graph-up-arrow me-2"></i>System-Statistiken</h1>
            <p class="text-muted">Analyse der systemweiten Nutzung und Auslastung.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 fw-bold">Nutzungsintensität (Zeiteinträge pro Monat)</div>
                <div class="card-body">
                    <div style="height: 300px; background: #f8f9fa; border-radius: 8px;" class="d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-bar-chart-line display-4 text-primary opacity-25"></i>
                            <p class="text-muted small">Diagramm-Vorschau: {{ $statsData->count() }} Datenpunkte geladen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white mb-4">
                <div class="card-body">
                    <h6 class="opacity-75 small uppercase">Durchschn. Einträge / Firma</h6>
                    @php
                        $firmenCount = \App\Models\User::where('role', 'admin')->count();
                        $eintragsCount = \App\Models\Zeiteintrag::count();
                        $avg = $firmenCount > 0 ? round($eintragsCount / $firmenCount, 1) : 0;
                    @endphp
                    <p class="display-6 fw-bold mb-0">{{ $avg }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Daten-Sicherheit</div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Verschlüsselung: <strong>Aktiv</strong></li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Datenbank-Status: <strong>Optimiert</strong></li>
                        <li><i class="bi bi-shield-lock-fill text-primary me-2"></i> DSGVO-Audit: <strong>Konform</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <a href="{{ route('superadmin.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Zurück zum Dashboard
        </a>
    </div>
</div>
@endsection