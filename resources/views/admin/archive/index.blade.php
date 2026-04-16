@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-archive-fill me-2"></i>Dokumenten-Archiv
        </h2>
        <div class="d-flex gap-2">
            <span class="badge bg-dark px-3 py-2 shadow-sm">GoBD-Konform</span>
            <span class="badge bg-secondary px-3 py-2 shadow-sm">DSGVO-Gesichert</span>
        </div>
    </div>

    {{-- Filter-Sektion --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light-subtle">
            <form action="{{ route('admin.archive.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Mitarbeiter</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Alle Mitarbeiter</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Dokumententyp</label>
                    <select name="type" class="form-select">
                        <option value="">Alle Typen</option>
                        <option value="intern" {{ request('type') == 'intern' ? 'selected' : '' }}>Intern (Arbeitszeitnachweis)</option>
                        <option value="extern" {{ request('type') == 'extern' ? 'selected' : '' }}>Extern (Leistungsnachweis)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Monat</label>
                    <select name="month" class="form-select">
                        <option value="">Alle Monate</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Jahr</label>
                    <select name="year" class="form-select">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm" title="Filtern">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.archive.index') }}" class="btn btn-outline-secondary w-100 shadow-sm" title="Filter zurücksetzen">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabelle --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Zeitraum</th>
                            <th>Typ & Versiegelung</th>
                            <th>Mitarbeiter</th>
                            <th>Status / Info</th>
                            <th class="text-end pe-4">Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archiv as $item)
                            @php
                                $isCancelled = !is_null($item->cancelled_at);
                            @endphp
                            <tr class="{{ $isCancelled ? 'table-danger' : '' }}" style="{{ $isCancelled ? 'background-color: #f8d7da !important;' : '' }}">
                                <td class="ps-4">
                                    <div class="fw-bold {{ $isCancelled ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                                        {{ \Carbon\Carbon::create($item->jahr, $item->monat, 1)->translatedFormat('F Y') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        Erstellt am: {{ $item->abgeschlossen_am ? $item->abgeschlossen_am->format('d.m.Y') : $item->created_at->format('d.m.Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($item->is_internal)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="width: fit-content;">
                                                <i class="bi bi-briefcase me-1"></i> Intern
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="width: fit-content;">
                                                <i class="bi bi-people me-1"></i> Extern
                                            </span>
                                        @endif

                                        @if($item->file_hash && !$isCancelled)
                                            <span class="badge bg-success-subtle text-success border d-inline-flex align-items-center" style="width: fit-content; font-size: 0.7rem;">
                                                <i class="bi bi-shield-lock-fill me-1"></i> 
                                                ID: {{ strtoupper(substr($item->file_hash, 0, 12)) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->user->name ?? 'Unbekannter User' }}</div>
                                </td>
                                <td>
                                    @if($isCancelled)
                                        <div class="text-danger fw-bold small">
                                            <i class="bi bi-x-octagon-fill me-1"></i> STORNIERT
                                            <div class="fw-normal text-muted" style="font-size: 0.7rem;">
                                                am {{ $item->cancelled_at->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                    @else
                                        @if($item->is_internal)
                                            <span class="text-muted italic small"><i class="bi bi-info-circle me-1"></i> Interne Abrechnung</span>
                                        @else
                                            <span class="text-muted small">
                                                <i class="bi bi-building me-1"></i> Schule: {{ $item->schule_unterzeichner ?? 'N/A' }}
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.archive.download', $item->id) }}" class="btn btn-sm btn-dark px-3 shadow-sm">
                                            <i class="bi bi-download me-1"></i> PDF
                                        </a>
                                        
                                        @if(!$isCancelled)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger shadow-sm ms-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#stornoModal" 
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->user->name }}"
                                                    data-date="{{ \Carbon\Carbon::create($item->jahr, $item->monat, 1)->translatedFormat('F Y') }}">
                                                <i class="bi bi-slash-circle"></i> Storno
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-folder2-open fs-1 text-secondary opacity-50"></i>
                                    </div>
                                    <h5 class="fw-light">Keine Dokumente gefunden</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i>Zurück zum Dashboard
        </a>
        <div>
            {{ $archiv->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<style>
    .italic { font-style: italic; }
    .bg-info-subtle { background-color: #e0f2fe; }
    .bg-success-subtle { background-color: #f0fdf4; }
    /* Wichtig für die Hintergrundfarbe trotz Bootstrap hover */
    .table-danger { --bs-table-bg: #f8d7da !important; }
</style>

{{-- STORNO MODAL OVERLAY --}}
<div class="modal fade" id="stornoModal" tabindex="-1" aria-labelledby="stornoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="stornoModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Abschluss stornieren
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="stornoForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="display-1 text-danger opacity-25 mb-2">
                            <i class="bi bi-shield-slash"></i>
                        </div>
                        <h4 class="fw-bold">Nachweis stornieren?</h4>
                        <p class="text-muted">
                            Sie stornieren den Abschluss für <strong id="stornoMitarbeiter"></strong> (<span id="stornoZeitraum"></span>).
                        </p>
                    </div>

                    <div class="alert alert-warning border-0 small">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>GoBD-Hinweis:</strong> Durch diesen Vorgang wird der bestehende Beleg ungültig. Die Zeiteinträge werden für manuelle Korrekturen freigegeben. Dieser Vorgang wird revisionssicher protokolliert.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Grund der Stornierung</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Warum muss dieser Abschluss aufgehoben werden?" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm">
                        <i class="bi bi-check-all me-1"></i> Stornierung bestätigen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const stornoModal = document.getElementById('stornoModal');
    if (stornoModal) {
        stornoModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const date = button.getAttribute('data-date');

            // 1. Texte setzen
            document.getElementById('stornoMitarbeiter').textContent = name;
            document.getElementById('stornoZeitraum').textContent = date;

            // 2. Formular-URL generieren
            const form = document.getElementById('stornoForm');
            
            // Nutze den Route-Namen und ersetze den Platzhalter
            let routeUrl = "{{ route('admin.archive.cancel', ':id') }}";
            form.action = routeUrl.replace(':id', id);
            
            console.log("Ziel-URL gesetzt auf: " + form.action);
        });
    }
</script>
@endsection