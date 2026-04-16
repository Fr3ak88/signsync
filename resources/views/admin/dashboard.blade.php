@extends('layouts.app')

@section('content')
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
            <a href="{{ route('zeiteintraege.create') }}" class="btn btn-success shadow-sm px-4 fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Neuen Einsatz buchen
            </a>
        </div>
    </div>

    {{-- Filter & Statistik --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <form action="{{ route('zeiteintraege.index') }}" method="GET" class="row g-2 w-100 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Zeitraum wählen</label>
                            <input type="month" name="month" class="form-control form-control-lg border-2" value="{{ request('month', now()->format('Y-m')) }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('zeiteintraege.index') }}" class="btn btn-outline-secondary btn-lg w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                    <h6 class="opacity-75 small text-uppercase fw-bold">Summe im Zeitraum</h6>
                    @php
                        $totalMinutes = $eintraege->sum(function($e) {
                            return \Carbon\Carbon::parse($e->start_zeit)->diffInMinutes(\Carbon\Carbon::parse($e->ende_zeit));
                        });
                        $totalHours = $totalMinutes / 60;
                    @endphp
                    <p class="display-6 fw-bold mb-0">{{ number_format($totalHours, 2, ',', '.') }} Std.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- E-Signatur Sektion --}}
    @php
        $aktuellerMonat = request('month', now()->format('Y-m'));
        $abschluss = \App\Models\Monatsabschluss::where('user_id', auth()->id())
                    ->where('monat', $aktuellerMonat)
                    ->first();
    @endphp

    <div class="card border-0 shadow-sm mb-4 {{ $abschluss ? 'bg-light border-start border-success border-4' : 'border-start border-warning border-4' }}">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">
                    @if($abschluss)
                        <i class="bi bi-shield-check text-success me-2"></i>Leistungsnachweis bestätigt
                    @else
                        <i class="bi bi-pen text-warning me-2"></i>Monatsabschluss fällig
                    @endif
                </h5>
                <p class="text-muted small mb-0">
                    @if($abschluss)
                        Bestätigt durch Schule ({{ $abschluss->schule_unterzeichner }}) und Mitarbeiter digital unterzeichnet am {{ $abschluss->unterzeichnet_am->format('d.m.Y H:i') }} Uhr.
                    @else
                        Bitte bestätigen Sie die Zeiten und lassen Sie den Nachweis von der Schule digital unterzeichnen.
                    @endif
                </p>
            </div>
            <div>
                @if(!$abschluss)
                    @if($eintraege->count() > 0)
                        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#signModal">
                            <i class="bi bi-vector-pen me-1"></i> Abschließen & Unterzeichnen
                        </button>
                    @endif
                @else
                    <a href="{{ route('zeiteintraege.export', ['month' => $aktuellerMonat]) }}" class="btn btn-dark fw-bold shadow-sm px-4">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF herunterladen
                    </a>
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
                            <th class="py-3">Schüler</th>
                            <th class="py-3">Zeitraum</th>
                            <th class="py-3 text-center">Dauer</th>
                            <th class="px-4 py-3 text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eintraege as $eintrag)
                            <tr class="{{ $abschluss ? 'opacity-75' : '' }}">
                                <td class="px-4 fw-bold text-dark">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('d.m.Y') }}</td>
                                <td>
                                    <span class="badge border text-dark fw-normal bg-white">
                                        {{ $eintrag->schueler->name ?? 'Unbekannt' }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} - {{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-success border border-success-subtle px-3 py-2">
                                        {{ number_format(\Carbon\Carbon::parse($eintrag->start_zeit)->diffInMinutes(\Carbon\Carbon::parse($eintrag->ende_zeit)) / 60, 2, ',', '.') }} Std.
                                    </span>
                                </td>
                                <td class="px-4 text-end">
                                    @if(!$abschluss)
                                        <div class="btn-group">
                                            <a href="{{ route('zeiteintraege.edit', $eintrag->id) }}" class="btn btn-sm btn-white border"><i class="bi bi-pencil text-primary"></i></a>
                                            <form action="{{ route('zeiteintraege.destroy', $eintrag->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white border" onclick="return confirm('Löschen?')"><i class="bi bi-trash text-danger"></i></button>
                                            </form>
                                        </div>
                                    @else
                                        <i class="bi bi-lock-fill text-muted"></i>
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

{{-- MODAL FÜR DOPPELTE UNTERSCHRIFT --}}
<div class="modal fade" id="signModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-lock me-2"></i>Monatsabschluss bestätigen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('zeiteintraege.sign') }}" method="POST" id="signature-form">
                @csrf
                <input type="hidden" name="month" value="{{ $aktuellerMonat }}">
                <input type="hidden" name="signature_data" id="signature_data">

                <div class="modal-body p-4 text-start">
                    
                    {{-- 1. BESTÄTIGUNG MITARBEITER --}}
                    <div class="mb-4 p-3 bg-light rounded border-start border-primary border-4 shadow-sm">
                        <h6 class="fw-bold text-primary mb-2 small text-uppercase">1. Bestätigung Schulbegleiter/in</h6>
                        <div class="form-check">
                            <input class="form-check-input border-primary shadow-none" type="checkbox" id="employee_confirm" name="employee_confirm" required>
                            <label class="form-check-label small" for="employee_confirm">
                                Ich, <strong>{{ auth()->user()->name }}</strong>, bestätige hiermit verbindlich die Richtigkeit und Vollständigkeit der oben aufgeführten Zeiten.
                            </label>
                        </div>
                    </div>

                    <div class="text-center my-3 text-muted opacity-50">
                        <i class="bi bi-arrow-down fs-4"></i>
                    </div>

                    {{-- 2. BESTÄTIGUNG SCHULE --}}
                    <div class="p-3 bg-light rounded border-start border-warning border-4 shadow-sm">
                        <h6 class="fw-bold text-warning mb-2 small text-uppercase">2. Bestätigung der Schule</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1 fw-bold">Name der Lehrkraft / Schulleitung</label>
                            <input type="text" name="schule_unterzeichner" class="form-control" placeholder="Vorname Nachname" required>
                        </div>

                        <label class="form-label small text-muted mb-1 fw-bold">Unterschrift (bitte im Feld zeichnen)</label>
                        <div class="border rounded bg-white overflow-hidden" style="height: 180px; position: relative;">
                            <canvas id="signature-pad" style="width: 100%; height: 100%; cursor: crosshair;"></canvas>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-1 text-decoration-none" id="clear-signature">
                            <i class="bi bi-eraser"></i> Feld leeren
                        </button>
                    </div>

                    <div class="alert alert-info mt-4 border-0 py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Nach dem Absenden wird dieser Monat für weitere Bearbeitungen gesperrt.
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                        <i class="bi bi-check-all me-1"></i> Dokument einreichen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;

        // Initialisierung ohne feste Größe
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        function resizeCanvas() {
            // Holen wir uns die tatsächliche Breite des Containers
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Leeren nach Resize ist leider technisch nötig
        }

        // Event-Listener für das Modal (Bootstrap 5)
        const signModal = document.getElementById('signModal');
        if (signModal) {
            signModal.addEventListener('shown.bs.modal', function () {
                // Kurze Verzögerung, damit CSS-Animationen fertig sind
                setTimeout(resizeCanvas, 100);
            });
        }

        // Fenster-Resize abfangen
        window.addEventListener("resize", resizeCanvas);

        // Formular-Handling
        const form = document.getElementById('signature-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    alert("Bitte lassen Sie die Schule zuerst im Feld unterschreiben.");
                    e.preventDefault();
                } else {
                    document.getElementById('signature_data').value = signaturePad.toDataURL('image/png');
                }
            });
        }

        // Clear Button
        const clearBtn = document.getElementById('clear-signature');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                signaturePad.clear();
            });
        }
    });
</script>
@endsection