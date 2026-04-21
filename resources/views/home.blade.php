@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark">SignSync Dashboard: {{ Auth::user()->company }}</h1>
            <p class="text-muted text-lg">
                Willkommen zurück, {{ Auth::user()->name }}. 
                @if(Auth::user()->role === 'admin')
                    Hier verwalten Sie Ihre Mitarbeiter und Klienten.
                @else
                    Hier können Sie Ihre Arbeitszeiten erfassen und einsehen.
                @endif
            </p>
        </div>
    </div>
    @if(Auth::user()->role === 'admin' && !Auth::user()->avv_accepted_at)
    <div class="alert alert-danger d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
        <div>
            <strong>Handlungsbedarf:</strong> Sie haben noch keinen Auftragsverarbeitungsvertrag (AVV) unterzeichnet. 
            Um SignSync rechtssicher zu nutzen, ist dies gesetzlich zwingend erforderlich.
            <a href="{{ route('admin.avv.show') }}" class="alert-link ms-2">Jetzt AVV prüfen & unterzeichnen</a>
        </div>
    </div>
    @endif
    @if(Auth::user()->role === 'admin')
        {{-- Statistik-Kacheln --}}
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Mitarbeiter</h5>
                        <p class="card-text display-6 fw-bold">{{ $employeeCount ?? 0 }}</p> 
                        <a href="/admin/employees" class="btn btn-light btn-sm text-primary fw-bold">
                            Verwalten →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Klienten / Schüler</h5>
                        <p class="card-text display-6 fw-bold">{{ \App\Models\Schueler::where('admin_id', Auth::id())->count() }}</p>
                        <a href="{{ route('admin.schueler.index') }}" class="btn btn-light btn-sm text-info fw-bold">
                            Liste öffnen →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Leistungsnachweise</h5>
                        <p class="card-text display-6 fw-bold">Offen</p>
                        <a href="{{ route('admin.arbeitsnachweise.index') }}" class="btn btn-dark btn-sm">Ansehen</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title opacity-75">Exports</h5>
                        <p class="card-text display-6 fw-bold">PDFs</p>
                        <a href="{{ route('admin.archive.index') }}" class="btn btn-light btn-sm text-success fw-bold">Archiv</a>
                    </div>
                </div>
            </div>
        </div>

       {{-- Schnellzugriff & Support nebeneinander --}}
<div class="row mt-2">
    {{-- Linke Spalte: Schnellzugriff --}}
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3 fw-bold">
                Schnellzugriff für {{ Auth::user()->company }}
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="/admin/employees" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                        <span><i class="bi bi-people me-2 text-primary"></i> Mitarbeiter-Verzeichnis</span>
                        <span class="badge bg-light text-primary rounded-pill border">→</span>
                    </a>
                    
                    <a href="{{ route('admin.schueler.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                        <span><i class="bi bi-mortarboard me-2 text-info"></i> Schüler-Datenbank</span>
                        <span class="badge bg-light text-info rounded-pill border">Pflegen</span>
                    </a>

                    <a href="/admin/positions" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
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

    {{-- Rechte Spalte: Hilfe & Abo --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Hilfe & Support</h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Angemeldet als:</small>
                    <span class="fw-bold">{{ Auth::user()->company }}</span>
                </div>

                {{-- Abo-Box --}}
                <div class="p-3 bg-light rounded border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold">Aktueller Plan:</span>
                        <span class="badge bg-primary">{{ ucfirst(Auth::user()->plan_name ?? 'Kein Plan') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Begleiter-Limit:</span>
                            <span class="fw-bold">{{ Auth::user()->employees()->count() }} / {{ Auth::user()->max_employees }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $count = Auth::user()->employees()->count();
                                $max = Auth::user()->max_employees > 0 ? Auth::user()->max_employees : 1;
                                $perc = ($count / $max) * 100;
                            @endphp
                            <div class="progress-bar {{ $perc > 100 ? 'bg-danger' : 'bg-success' }}" 
                                 role="progressbar" style="width: {{ min($perc, 100) }}%"></div>
                        </div>
                        @if($perc > 100)
                            <small class="text-danger mt-1 d-block" style="font-size: 0.7rem;">
                                Limit überschritten! Bitte Paket upgraden.
                            </small>
                        @endif
                    </div>

                    <hr class="my-3 opacity-10">

                    {{-- Kündigen Button --}}
                    <form action="{{ route('plans.cancel') }}" method="POST" onsubmit="return confirm('Möchten Sie Ihr Abo wirklich kündigen? Ihre Daten bleiben erhalten, aber Sie können keine neuen Einträge mehr vornehmen.');">
                        @csrf
                        {{-- Kündigen Button (öffnet Modal) --}}
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#confirmCancelModal">
                            <i class="bi bi-x-circle me-1"></i> Abonnement kündigen
                        </button>
                    </form>
                </div>

                <p class="small text-muted mb-2">Bei Fragen kontaktieren Sie bitte unseren Support.</p>
                <a href="mailto:support@signsync.de" class="btn btn-outline-secondary btn-sm w-100 py-2 fw-bold mb-3">
                    Support kontaktieren
                </a>
                
                <div class="text-center">
                    <p class="small text-success mb-0" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-check me-1"></i> DSGVO-konforme Datenhaltung aktiv.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> {{-- Ende der row --}}

    @else
        {{-- Mitarbeiter-Zentrale --}}
<div class="container py-4">
    {{-- Obere Reihe: Aktions-Karten --}}
    <div class="row g-4 mb-4">
        {{-- Karte A: Schülerbegleitung --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold">Schülerbegleitung</h4>
                    <p class="text-muted small">Erfasse Zeiten für deine zugewiesenen Schüler. Diese Zeiten erscheinen auf dem Leistungsnachweis für das Amt.</p>
                    <a href="{{ route('zeiteintraege.create') }}" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Zeit für Schüler erfassen
                    </a>
                </div>
            </div>
        </div>

        {{-- Karte B: Interne Arbeitszeit --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-info">
                        <i class="bi bi-briefcase-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold">Interne Arbeitszeit</h4>
                    <p class="text-muted small">Erfasse Büroarbeit, Team-Meetings, Fortbildungen oder Fahrtzeiten. Diese Zeiten sind nur für deine interne Abrechnung.</p>
                    <a href="{{ route('worktime.create') }}" class="btn btn-info text-white w-100 fw-bold shadow-sm">
                        <i class="bi bi-clock-history me-1"></i> Arbeitszeit erfassen
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Mittige Monats-Statistik (Gleicher Style wie oben, volle Breite) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body text-center p-4">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">
                        <i class="bi bi-calendar-check me-1"></i> Arbeitsstunden im {{ now()->locale('de')->monthName }}
                    </h6>
                    
                    @php
                        $monatsStunden = Auth::user()->zeiteintraege()
                            ->whereMonth('start_zeit', now()->month)
                            ->whereYear('start_zeit', now()->year)
                            ->get()
                            ->sum(function($e) {
                                $start = \Carbon\Carbon::parse($e->start_zeit);
                                $ende = \Carbon\Carbon::parse($e->ende_zeit);
                                return $start->diffInMinutes($ende) / 60;
                            });
                    @endphp

                    <div class="mb-2">
                        <span class="display-4 fw-bold text-success">{{ number_format($monatsStunden, 2, ',', '.') }}</span>
                        <span class="h4 text-muted">Std.</span>
                    </div>
                    
                    <p class="small text-muted mb-3">Gesamtzeit aller Einsätze und Bürozeiten diesen Monat.</p>

                    <a href="{{ route('zeiteintraege.index') }}" class="btn btn-outline-success btn-sm fw-bold px-4">
                        <i class="bi bi-list-check me-1"></i> Alle Einträge anzeigen
                    </a>
                </div>
            </div>
        </div>
    </div>

{{-- Meine Dokumente & Archiv --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-file-earmark-lock-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Dokumente & Archiv</h6>
                    <p class="text-muted small mb-0">Alle signierten Leistungs- und Arbeitsnachweise einsehen.</p>
                </div>
            </div>
            {{-- Die Route wird um den Parameter 'view=archiv' ergänzt --}}
            <a href="{{ route('monatsabschluss.archiv') }}" class="btn btn-outline-warning fw-bold px-4">
    Zum Archiv <i class="bi bi-arrow-right ms-1"></i>
</a>
        </div>
    </div>
</div>

        {{-- Optional: Kurzhilfe für Mitarbeiter --}}
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-muted small">
                    <i class="bi bi-shield-lock me-1"></i> Ihre Daten werden sicher und DSGVO-konform für die Abrechnung verarbeitet.
                </p>
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="confirmCancelModalLabel">Abonnement kündigen?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-dark fw-bold">Sind Sie sicher, dass Sie Ihr Abonnement kündigen möchten?</p>
                <ul class="text-muted small">
                    <li>Ihre bestehenden Daten (Mitarbeiter, Schüler) bleiben erhalten.</li>
                    <li>Sie können ab sofort **keine neuen Einträge** mehr vornehmen.</li>
                    <li>Sie können SignSync jederzeit durch eine neue Buchung reaktivieren.</li>
                </ul>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Abbrechen</button>
                <form action="{{ route('plans.cancel') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger fw-bold px-4">Ja, jetzt kündigen</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection