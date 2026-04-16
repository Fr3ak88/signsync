@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="bi bi-clipboard-check-fill text-info me-2"></i>Status Arbeitsnachweise
        </h2>
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i>Zurück zum Dashboard
        </a>
    </div>

    {{-- Filter-Sektion --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light-subtle">
            <form action="{{ route('admin.arbeitsnachweise.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-info">Monat wählen</label>
                    <select name="month" class="form-select form-select-sm">
                        @for($m=1; $m<=12; $m++)
                            @php $mVal = sprintf('%02d', $m); @endphp
                            <option value="{{ $mVal }}" {{ $selectedMonth == $mVal ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-info">Jahr</label>
                    <select name="year" class="form-select form-select-sm">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info btn-sm text-white px-3 shadow-sm">
                        <i class="bi bi-search me-1"></i> Anzeigen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4 py-3">MITARBEITER</th>
                            <th>STUNDEN-AUFTEILUNG</th>
                            <th>GESAMT</th>
                            <th>STATUS</th>
                            <th class="text-end pe-4">ABSCHLUSS AM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr class="{{ $report['is_signed'] ? 'bg-light-subtle' : '' }}">
                                <td class="ps-4">
                                    {{-- Der Link übergibt die User-ID sowie Monat/Jahr aus dem aktuellen Filter --}}
                                    <a href="{{ route('admin.arbeitsnachweise.show', ['user' => $report['user_id'], 'month' => $selectedMonth, 'year' => $selectedYear]) }}" 
                                        class="fw-bold text-info text-decoration-none">
                                        <i class="bi bi-person-circle me-1"></i> {{ $report['name'] }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="small">
                                            <i class="bi bi-house-door text-info me-1"></i>
                                            <span class="text-muted">Intern:</span> 
                                            <span class="fw-bold">{{ $report['hours_internal'] }} h</span>
                                        </div>
                                        <div class="small">
                                            <i class="bi bi-person-walking text-primary me-1"></i>
                                            <span class="text-muted">Extern:</span> 
                                            <span class="fw-bold">{{ $report['hours_external'] }} h</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-dark px-2 py-1">{{ $report['total_hours'] }} h</span>
                                </td>
                                <td>
                                    @if($report['is_signed'])
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center">
                                                <i class="bi bi-check-circle-fill me-1"></i> Signiert
                                            </span>
                                            {{-- GoBD Siegel Badge --}}
                                            <span class="badge bg-info-subtle text-info border border-info-subtle d-inline-flex align-items-center shadow-sm" 
                                                  style="cursor: help; width: fit-content;" 
                                                  title="Digital versiegelt (SHA-256). Dieses Dokument ist revisionssicher archiviert.">
                                                <i class="bi bi-shield-lock-fill me-1"></i> Versiegelt
                                            </span>
                                        </div>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle animate-pulse">
                                            <i class="bi bi-clock-history me-1"></i> Offen
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 small text-muted">
                                    @if($report['is_signed'])
                                        <span class="text-dark">{{ $report['signed_at'] }}</span>
                                    @else
                                        <span class="text-danger italic">ausstehend</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic">
                                    <i class="bi bi-info-circle me-2"></i>Keine Einträge für diesen Zeitraum gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.6; } 100% { opacity: 1; } }
    .bg-light-subtle { background-color: #fcfcfc; }
    .italic { font-style: italic; }
    /* Tooltip Styling Optimierung */
    .badge[title] { position: relative; }
</style>
@endsection