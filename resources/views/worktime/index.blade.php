@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-info mb-0">
            <i class="bi bi-briefcase-fill me-2"></i>Interne Arbeitszeit
        </h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Zum Dashboard
        </a>
    </div>

    {{-- Filter-Sektion --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light-subtle">
            <form action="{{ route('worktime.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-info">Monat</label>
                    <select name="month" class="form-select form-select-sm">
                        @for($m=1; $m<=12; $m++)
                            @php $mVal = sprintf('%02d', $m); @endphp
                            <option value="{{ $mVal }}" {{ request('month', now()->format('m')) == $mVal ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-info">Jahr</label>
                    <select name="year" class="form-select form-select-sm">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info btn-sm text-white px-3 shadow-sm">
                        <i class="bi bi-filter me-1"></i> Filtern
                    </button>
                    <a href="{{ route('worktime.index') }}" class="btn btn-link btn-sm text-secondary text-decoration-none">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- Linke Spalte: Formular zum Erfassen --}}
        <div class="col-md-4">
            @if($isAbgeschlossen)
                <div class="alert alert-success border-0 shadow-sm border-start border-4 border-success">
                    <h5 class="fw-bold"><i class="bi bi-lock-fill me-2"></i>Abgeschlossen</h5>
                    <p class="small mb-0">Dieser Monat wurde bereits signiert und abgeschlossen. Es können keine weiteren Zeiten erfasst werden.</p>
                </div>
            @else
                <div class="card border-0 shadow-sm border-start border-4 border-info">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="bi bi-plus-lg me-1 text-info"></i> Zeit erfassen
                    </div>
                    <div class="card-body">
                        <form action="{{ route('worktime.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Startzeit</label>
                                <input type="datetime-local" name="start_zeit" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Endzeit</label>
                                <input type="datetime-local" name="ende_zeit" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tätigkeit / Notiz</label>
                                <textarea name="taetigkeit" class="form-control" rows="3" placeholder="z.B. Team-Meeting, Fahrtzeit..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-info text-white w-100 fw-bold shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Speichern
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Rechte Spalte: Liste der Einträge --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="fw-bold"><i class="bi bi-list-ul me-1 text-info"></i> Einträge</span>
                    
                    @if($isAbgeschlossen)
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success py-2 px-3">
                                <i class="bi bi-check-all me-1"></i> Signiert
                            </span>
                            <form action="{{ route('worktime.export') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="month" value="{{ request('month', now()->format('m')) }}">
                                <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                                <input type="hidden" name="re_download" value="1">
                                <button type="submit" class="btn btn-sm btn-outline-info shadow-sm fw-bold">
                                    <i class="bi bi-download me-1"></i> Erneut herunterladen
                                </button>
                            </form>
                        </div>
                    @elseif(!$istExportErlaubt)
                        <button type="button" class="btn btn-sm btn-outline-secondary disabled shadow-sm" title="Erst ab dem 23. des Monats verfügbar">
                            <i class="bi bi-calendar-x me-1"></i> Export ab dem 23.
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-info text-white fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Abschluss & Download
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Datum</th>
                                    <th>Zeitraum</th>
                                    <th>Tätigkeit</th>
                                    <th class="text-end pe-3">Dauer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eintraege as $e)
                                    @php
                                        $start = \Carbon\Carbon::parse($e->start_zeit);
                                        $ende = \Carbon\Carbon::parse($e->ende_zeit);
                                        $stunden = round($start->diffInMinutes($ende) / 60, 2);
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-bold">{{ $start->format('d.m.Y') }}</td>
                                        <td class="small text-muted">
                                            {{ $start->format('H:i') }} - {{ $ende->format('H:i') }}
                                        </td>
                                        <td class="small">{{ Str::limit($e->notizen, 40) ?: '-' }}</td>
                                        <td class="text-end pe-3 fw-bold text-info">{{ number_format($stunden, 2, ',', '.') }} h</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            Keine Einträge für diesen Zeitraum gefunden.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($eintraege->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $eintraege->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal für die Monats-Unterschrift --}}
@if(!$isAbgeschlossen && $istExportErlaubt)
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('worktime.export') }}" method="POST" id="export-form">
                @csrf 
                <input type="hidden" name="month" value="{{ request('month', now()->format('m')) }}">
                <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                <input type="hidden" name="signature" id="signature-input">

                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pen-fill me-2"></i>Monat abschließen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="small text-muted mb-3">
                        Hiermit bestätigst du die Richtigkeit deiner Angaben für <strong>{{ \Carbon\Carbon::create(now()->year, (int)request('month', now()->month), 1)->translatedFormat('F') }} {{ request('year', now()->year) }}</strong>.
                    </p>
                    
                    <div class="signature-wrapper border rounded bg-white mb-1" style="position: relative; width: 100%; height: 200px;">
                        <canvas id="signature-pad" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; touch-action: none; cursor: crosshair;"></canvas>
                    </div>
                    
                    <div class="mt-2 d-flex justify-content-between">
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="clearPad()">
                            <i class="bi bi-eraser"></i> Löschen
                        </button>
                        <small class="text-muted italic">Bitte Feld unterschreiben</small>
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">
                        <i class="bi bi-file-earmark-check me-1"></i> Signieren & Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById("signature-pad");
        if (!canvas) return;

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        const exportModal = document.getElementById('exportModal');
        exportModal.addEventListener('shown.bs.modal', function () {
            resizeCanvas();
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            const context = canvas.getContext("2d");
            context.fillStyle = "white";
            context.fillRect(0, 0, canvas.width, canvas.height);
            signaturePad.clear();
        }

        const form = document.getElementById('export-form');
        form.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                alert("Bitte unterschreibe den Nachweis, bevor du den Monat abschließt.");
                e.preventDefault();
            } else {
                document.getElementById('signature-input').value = signaturePad.toDataURL('image/jpeg', 0.5);

                setTimeout(() => {
                    const modalElement = document.getElementById('exportModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 100);
            }
        });

        window.clearPad = function() {
            signaturePad.clear();
            const context = canvas.getContext("2d");
            context.fillStyle = "white";
            context.fillRect(0, 0, canvas.width, canvas.height);
        };
    });
</script>
@endsection