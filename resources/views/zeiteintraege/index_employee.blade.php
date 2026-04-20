@extends('layouts.app')

@section('content')
<style>
    #employee-signature-pad, #school-signature-pad {
        width: 100% !important;
        height: 200px !important; 
        touch-action: none; 
        background-color: #fff;
        border: 1px solid #ced4da;
    }
    canvas {
    cursor: crosshair;
    touch-action: none;
    display: block;
    width: 100% !important;
    height: 100% !important;
}
</style>
<div class="container text-start">
    {{-- Navigationszeile --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ url('/dashboard') }}" class="btn btn-link text-decoration-none text-muted p-0">
                <i class="bi bi-chevron-left"></i> Zurück zum Dashboard
            </a>
        </div>
    </div>

    {{-- Titel Bereich --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold text-success mb-0">
                <i class="bi bi-clock-history me-2"></i>Meine Zeiteinträge
            </h1>
            <p class="text-muted mt-1">Historie deiner erfassten Einsätze.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group shadow-sm">
                <a href="{{ route('zeiteintraege.create') }}" class="btn btn-success fw-bold">
                    <i class="bi bi-plus-circle me-1"></i> Einsatz buchen
                </a>
                <a href="{{ route('worktime.create') }}" class="btn btn-info text-white fw-bold">
                    <i class="bi bi-briefcase me-1"></i> Intern erfassen
                </a>
            </div>
        </div>
    </div>

    {{-- Filter & Statistik --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <form action="{{ route('zeiteintraege.index') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Zeitraum</label>
                            <input type="month" name="month" class="form-control border-2" value="{{ request('month', now()->format('Y-m')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Typ</label>
                            <select name="typ" class="form-select border-2">
                                <option value="">Alle Einträge</option>
                                <option value="extern" {{ request('typ') == 'extern' ? 'selected' : '' }}>Schülerbegleitung (Extern)</option>
                                <option value="arbeit" {{ request('typ') == 'arbeit' ? 'selected' : '' }}>Büro/Intern (Intern)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('zeiteintraege.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                    <h6 class="opacity-75 small text-uppercase fw-bold">Netto-Summe (Filter)</h6>
                    @php
                        $totalMinutes = $eintraege->sum(function($e) {
                            $brutto = \Carbon\Carbon::parse($e->start_zeit)->diffInMinutes(\Carbon\Carbon::parse($e->ende_zeit));
                            return $brutto - ($e->pause_minuten ?? 0);
                        });
                        $totalHours = $totalMinutes / 60;
                    @endphp
                    <p class="display-6 fw-bold mb-0">{{ number_format($totalHours, 2, ',', '.') }} h</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $aktuellerMonatStr = request('month', now()->format('Y-m'));
        $datumObjekt = \Carbon\Carbon::parse($aktuellerMonatStr . '-01');
        
        // Externer Abschluss (Leistungsnachweis)
        $abschlussExtern = \App\Models\Monatsabschluss::where('user_id', auth()->id())
                    ->where('monat', $datumObjekt->month)
                    ->where('jahr', $datumObjekt->year)
                    ->where('is_internal', false)
                    ->whereNull('cancelled_at')
                    ->first();

        // Interner Abschluss (Arbeitsnachweis)
        $abschlussIntern = \App\Models\Monatsabschluss::where('user_id', auth()->id())
                    ->where('monat', $datumObjekt->month)
                    ->where('jahr', $datumObjekt->year)
                    ->where('is_internal', true)
                    ->whereNull('cancelled_at')
                    ->first();

        $istAktuellerMonat = $datumObjekt->isCurrentMonth();
        $startErlaubt = $datumObjekt->copy()->endOfMonth()->subDays(7);
        $istGesperrt = $istAktuellerMonat && now()->lt($startErlaubt);
    @endphp

    {{-- Sektion EXTERNER Abschluss --}}
    <div class="card border-0 shadow-sm mb-3 {{ $abschlussExtern ? 'bg-light border-start border-success border-4' : ($istGesperrt ? 'border-start border-secondary border-4 bg-light' : 'border-start border-warning border-4') }}">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">
                    @if($abschlussExtern)
                        <i class="bi bi-shield-check text-success me-2"></i>Leistungsnachweis bestätigt (Extern)
                    @elseif($istGesperrt)
                        <i class="bi bi-lock text-muted me-2"></i>Externer Abschluss noch gesperrt
                    @else
                        <i class="bi bi-pen text-warning me-2"></i>Monatsabschluss fällig (Extern)
                    @endif
                </h5>
                <p class="text-muted small mb-0">
                    @if($abschlussExtern)
                        Bestätigt durch Schule ({{ $abschlussExtern->schule_unterzeichner }}) am {{ \Carbon\Carbon::parse($abschlussExtern->abgeschlossen_am)->format('d.m.Y H:i') }} Uhr.
                    @else
                        Nachweis der Begleitstunden für die Abrechnung mit dem Kostenträger.
                    @endif
                </p>
            </div>
            <div>
                @if(!$abschlussExtern)
                    @if(!$istGesperrt)
                        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#signModal">
                            <i class="bi bi-vector-pen me-1"></i> Jetzt abschließen
                        </button>
                    @endif
                @else
                    <a href="{{ route('zeiteintraege.export', ['month' => $aktuellerMonatStr]) }}" class="btn btn-dark fw-bold shadow-sm px-4">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Sektion INTERNER Abschluss (NEU) --}}
    <div class="card border-0 shadow-sm mb-4 {{ $abschlussIntern ? 'bg-light border-start border-primary border-4' : ($istGesperrt ? 'border-start border-secondary border-4 bg-light' : 'border-start border-info border-4') }}">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1 text-info">
                    @if($abschlussIntern)
                        <i class="bi bi-check-all text-primary me-2"></i>Arbeitsnachweis versiegelt (Intern)
                    @elseif($istGesperrt)
                        <i class="bi bi-lock text-muted me-2"></i>Interner Abschluss noch gesperrt
                    @else
                        <i class="bi bi-file-earmark-lock text-info me-2"></i>Monatsabschluss fällig (Intern)
                    @endif
                </h5>
                <p class="text-muted small mb-0">
                    @if($abschlussIntern)
                        Ihr interner Tätigkeitsnachweis wurde am {{ \Carbon\Carbon::parse($abschlussIntern->abgeschlossen_am)->format('d.m.Y') }} Uhr finalisiert.
                    @else
                        Bestätigung Ihrer bürointernen Zeiten und Fahrtzeiten für die Lohnabrechnung.
                    @endif
                </p>
            </div>
            <div>
                @if(!$abschlussIntern)
                    @if(!$istGesperrt)
                        {{-- Link zum WorktimeController Export/Sign Logik --}}
                        <form action="{{ route('worktime.export') }}" method="POST">
                            @csrf
                            <input type="hidden" name="month" value="{{ $datumObjekt->month }}">
                            <input type="hidden" name="year" value="{{ $datumObjekt->year }}">
                            <button type="button" class="btn btn-info text-white fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="bi bi-pen-fill me-1"></i> Intern signieren
                            </button>
                        </form>
                    @endif
                @else
                    <form action="{{ route('worktime.export') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="month" value="{{ $datumObjekt->month }}">
                        <input type="hidden" name="year" value="{{ $datumObjekt->year }}">
                        <input type="hidden" name="re_download" value="1">
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">
                            <i class="bi bi-download me-1"></i> PDF
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabelle --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Datum</th>
                            <th class="py-3">Typ / Details</th>
                            <th class="py-3">Zeitraum</th>
                            <th class="py-3 text-center">Netto</th>
                            <th class="px-4 py-3 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eintraege as $eintrag)
                            @php
                                $istGesperrtZeile = ($eintrag->typ === 'arbeit' && $abschlussIntern) || ($eintrag->typ === 'leistung' && $abschlussExtern);
                            @endphp
                            <tr class="{{ $istGesperrtZeile ? 'opacity-75 bg-light' : '' }}">
                                <td class="px-4 fw-bold text-dark">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('d.m.Y') }}</td>
                                <td>
                                    @if($eintrag->typ === 'arbeit')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle fw-normal">Intern</span>
                                        <small class="text-muted ms-1">{{ Str::limit($eintrag->notiz, 30) }}</small>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal">Schüler</span>
                                        <small class="text-dark ms-1">{{ $eintrag->schueler->name ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    {{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} - {{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $min = \Carbon\Carbon::parse($eintrag->start_zeit)->diffInMinutes(\Carbon\Carbon::parse($eintrag->ende_zeit)) - ($eintrag->pause_minuten ?? 0);
                                    @endphp
                                    <span class="fw-bold">{{ number_format($min / 60, 2, ',', '.') }} h</span>
                                </td>
                                <td class="px-4 text-end">
    {{-- Wir prüfen: Ist die einzelne Zeile in der DB gesperrt ODER gibt es einen Monatsabschluss --}}
    @if($eintrag->is_locked || $istGesperrtZeile)
        <span class="text-muted" title="Dieser Eintrag ist versiegelt und kann nicht mehr bearbeitet werden.">
            <i class="bi bi-lock-fill"></i>
            <small class="ms-1 d-none d-md-inline">Gespert</small>
        </span>
    @else
        <span class="text-success opacity-75" title="Eintrag ist offen">
            <i class="bi bi-unlock"></i>
            <small class="ms-1 d-none d-md-inline">Offen</small>
        </span>
    @endif
</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Keine Einträge gefunden.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FÜR EXTERNEN ABSCHLUSS (Schule) --}}
{{-- MODAL FÜR EXTERNEN ABSCHLUSS (Schule) --}}
<div class="modal fade" id="signModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- modal-lg für mehr Platz zum Unterschreiben --}}
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-shield-lock me-2"></i>Externer Monatsabschluss
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('zeiteintraege.sign') }}" method="POST" id="signature-form">
                @csrf
                <input type="hidden" name="month" value="{{ $aktuellerMonatStr }}">
                <input type="hidden" name="signature_data" id="signature_data">
                <input type="hidden" name="employee_signature" id="employee_signature">

                <div class="modal-body p-4 text-start">
                    {{-- 1. MITARBEITER --}}
                    <div class="mb-4 p-3 bg-light rounded border-start border-primary border-4 shadow-sm">
                        <h6 class="fw-bold text-primary mb-2 small text-uppercase">1. Unterschrift Schulbegleiter/in</h6>
                        <div class="border rounded bg-white overflow-hidden" style="height:180px; position: relative;">
                            <canvas id="employee-signature-pad" style="width:100%; height:180px; touch-action: none;"></canvas>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-1" id="clear-employee-signature">
                            <i class="bi bi-eraser"></i> Feld leeren
                        </button>
                        <div class="form-check mt-2">
                            <input class="form-check-input border-primary shadow-none" type="checkbox" id="employee_confirm" name="employee_confirm" required>
                            <label class="form-check-label small" for="employee_confirm">
                                Ich, <strong>{{ auth()->user()->name }}</strong>, bestätige die Richtigkeit dieser Leistungsnachweise.
                            </label>
                        </div>
                    </div>

                    {{-- 2. SCHULE --}}
                    <div class="p-3 bg-light rounded border-start border-warning border-4 shadow-sm">
                        <h6 class="fw-bold text-warning mb-2 small text-uppercase">2. Bestätigung der Schule (Lehrkraft/Leitung)</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Name der unterzeichnenden Person</label>
                            <input type="text" name="schule_unterzeichner" class="form-control border-2" placeholder="Vorname Nachname" required>
                        </div>
                        <div class="border rounded bg-white overflow-hidden" style="height:180px; position: relative;">
                            <canvas id="school-signature-pad" style="width:100%; height:180px; touch-action: none;"></canvas>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-1" id="clear-school-signature">
                            <i class="bi bi-eraser"></i> Feld leeren
                        </button>
                    </div>

                    <div class="alert alert-info mt-4 border-0 py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Nach dem Absenden wird dieser Zeitraum für externe Einträge gesperrt.
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                        <i class="bi bi-check-all me-1"></i> Leistungsnachweis einreichen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL FÜR INTERNEN ABSCHLUSS (Mitarbeiter Signatur) --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-pen-fill me-2"></i>Interner Arbeitsnachweis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('worktime.export') }}" method="POST" id="internal-sign-form">
                @csrf
                <input type="hidden" name="month" value="{{ $datumObjekt->month }}">
                <input type="hidden" name="year" value="{{ $datumObjekt->year }}">
                <input type="hidden" name="signature" id="internal_signature_data">
                
                <div class="modal-body p-4">
                    <p class="text-muted small">Hiermit bestätigen Sie Ihre internen Tätigkeiten (Büro, Fahrtzeiten, Fortbildung) für den Monat {{ $datumObjekt->translatedFormat('F Y') }}.</p>
                    <div class="border rounded bg-white overflow-hidden" style="height:200px;">
                        <canvas id="internal-signature-pad" style="width:100%; height:200px; cursor:crosshair;"></canvas>
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-2" id="clear-internal-sig">Feld leeren</button>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary fw-bold w-100 py-2 shadow-sm">Signieren & Versiegeln</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
let employeePad, schoolPad, internalPad;

// Funktion zum Initialisieren und Skalieren
function setupPad(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;

    // WICHTIG: Canvas-Größe explizit setzen, bevor Pad erstellt wird
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    return new SignaturePad(canvas, { 
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });
}

// Event für Extern-Modal (Schule)
document.getElementById('signModal').addEventListener('shown.bs.modal', function () {
    // Falls noch nicht erstellt oder durch Fenster-Resize kaputt
    employeePad = setupPad('employee-signature-pad');
    schoolPad = setupPad('school-signature-pad');
});

// Event für Intern-Modal (Mitarbeiter)
document.getElementById('exportModal').addEventListener('shown.bs.modal', function () {
    internalPad = setupPad('internal-signature-pad');
});

// Lösch-Funktionen
document.getElementById('clear-employee-signature').addEventListener('click', function(e) {
    e.preventDefault(); if(employeePad) employeePad.clear();
});
document.getElementById('clear-school-signature').addEventListener('click', function(e) {
    e.preventDefault(); if(schoolPad) schoolPad.clear();
});
document.getElementById('clear-internal-sig').addEventListener('click', function(e) {
    e.preventDefault(); if(internalPad) internalPad.clear();
});

// Formular-Validierung Extern
document.getElementById('signature-form').addEventListener('submit', function(e) {
    if (!employeePad || employeePad.isEmpty() || !schoolPad || schoolPad.isEmpty()) {
        alert("Bitte beide Felder (Mitarbeiter & Schule) unterschreiben.");
        e.preventDefault();
        return;
    }
    document.getElementById('employee_signature').value = employeePad.toDataURL();
    document.getElementById('signature_data').value = schoolPad.toDataURL();
});

// Formular-Validierung Intern
document.getElementById('internal-sign-form').addEventListener('submit', function(e) {
    if (!internalPad || internalPad.isEmpty()) {
        alert("Bitte unterschreiben Sie das Feld.");
        e.preventDefault();
        return;
    }
    
    // Daten in das Hidden-Feld schreiben
    document.getElementById('internal_signature_data').value = internalPad.toDataURL();
    
    // MODAL SCHLIESSEN: Wir holen uns die Bootstrap Instanz und schließen sie
    const modalElement = document.getElementById('exportModal');
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    
    // Kurze Verzögerung, damit die Daten sicher übertragen werden
    setTimeout(() => {
        if (modalInstance) modalInstance.hide();
    }, 100);

    // Seite nach 2 Sekunden neu laden, falls der Download den Reload verhindert
    setTimeout(() => {
        window.location.reload();
    }, 2000);
});
</script>
@endsection