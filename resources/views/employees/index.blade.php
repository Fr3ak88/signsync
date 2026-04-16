@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-archive-fill me-2"></i>Mein Dokumenten-Archiv
        </h2>
        <div class="d-flex gap-2">
            <span class="badge bg-dark px-3 py-2 shadow-sm">GoBD-Konform</span>
        </div>
    </div>

    {{-- Filter-Sektion (Vereinfacht für Mitarbeiter) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light-subtle">
            <form action="{{ route('monatsabschluss.archiv') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Dokumententyp</label>
                    <select name="type" class="form-select">
                        <option value="">Alle Typen</option>
                        <option value="intern" {{ request('type') == 'intern' ? 'selected' : '' }}>Intern (Arbeitszeitnachweis)</option>
                        <option value="extern" {{ request('type') == 'extern' ? 'selected' : '' }}>Extern (Leistungsnachweis)</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jahr</label>
                    <select name="year" class="form-select">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="bi bi-search"></i></button>
                    <a href="{{ route('monatsabschluss.archiv') }}" class="btn btn-outline-secondary w-100 shadow-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                            <th>Info</th>
                            <th class="text-end pe-4">Aktion</th>
                        </tr>
                    </thead>
<tbody>
    @forelse($archiv as $item)
        @php $isCancelled = !is_null($item->cancelled_at); @endphp
        <tr class="{{ $isCancelled ? 'table-danger' : '' }}">
            <td class="ps-4">
                <div class="fw-bold {{ $isCancelled ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                    {{ \Carbon\Carbon::create($item->year ?? $item->jahr, $item->month ?? $item->monat, 1)->translatedFormat('F Y') }}
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">
                    Beleg vom: {{ $item->abgeschlossen_am ? \Carbon\Carbon::parse($item->abgeschlossen_am)->format('d.m.Y') : $item->created_at->format('d.m.Y') }}
                </small>
            </td>
            <td>
                <div class="d-flex flex-column gap-1">
                    <span class="badge {{ $item->is_internal ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' }} border px-2 py-1" style="width: fit-content;">
                        <i class="bi {{ $item->is_internal ? 'bi-briefcase' : 'bi-people' }} me-1"></i> 
                        {{ $item->is_internal ? 'Intern' : 'Extern' }}
                    </span>
                    @if($item->file_hash && !$isCancelled)
                        <span class="badge bg-success-subtle text-success border d-inline-flex align-items-center" style="width: fit-content; font-size: 0.7rem;">
                            <i class="bi bi-shield-lock-fill me-1"></i> ID: {{ strtoupper(substr($item->file_hash, 0, 12)) }}
                        </span>
                    @endif
                </div>
            </td>
            <td>
                @if($isCancelled)
                    <span class="text-danger fw-bold small"><i class="bi bi-x-octagon-fill me-1"></i> STORNIERT</span>
                @else
                    <span class="text-muted small">
                        <i class="bi bi-check2-circle text-success me-1"></i> Finalisiert
                    </span>
                @endif
            </td>
            <td class="text-end pe-4">
                @if(!$isCancelled)
                    <form action="{{ $item->is_internal ? route('worktime.export') : route('zeiteintraege.export') }}" method="{{ $item->is_internal ? 'POST' : 'GET' }}">
                        @csrf
                        <input type="hidden" name="month" value="{{ $item->is_internal ? $item->monat : \Carbon\Carbon::create($item->jahr, $item->monat)->format('Y-m') }}">
                        <input type="hidden" name="year" value="{{ $item->jahr }}">
                        <input type="hidden" name="re_download" value="1">
                        <button type="submit" class="btn btn-sm btn-dark">
                            <i class="bi bi-download me-1"></i> PDF
                        </button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-center py-5 text-muted">Keine Dokumente im Archiv.</td></tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-start mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i>Dashboard
        </a>
    </div>
</div>
@endsection