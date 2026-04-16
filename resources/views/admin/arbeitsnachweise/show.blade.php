@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Details: {{ $user->name }}</h2>
            <p class="text-muted">{{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</p>
        </div>
        <a href="{{ route('admin.arbeitsnachweise.index', ['month' => $month, 'year' => $year]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Zurück zur Übersicht
        </a>
    </div>

    {{-- Filter-Tabs --}}
    <div class="mb-4">
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ request()->fullUrlWithQuery(['typ' => '']) }}" 
               class="btn {{ request('typ') == '' ? 'btn-dark' : 'btn-outline-dark' }}">
                Alle Einträge
            </a>
            <a href="{{ request()->fullUrlWithQuery(['typ' => 'arbeit']) }}" 
               class="btn {{ request('typ') == 'arbeit' ? 'btn-info text-white' : 'btn-outline-info' }}">
                <i class="bi bi-house-door me-1"></i> Intern (Arbeit)
            </a>
            <a href="{{ request()->fullUrlWithQuery(['typ' => 'begleitung']) }}" 
               class="btn {{ request('typ') == 'begleitung' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-person-walking me-1"></i> Extern (Begleitung)
            </a>
        </div>
    </div>

    @if($abschluss)
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                Dieser Monat wurde am {{ \Carbon\Carbon::parse($abschluss->abgeschlossen_am)->format('d.m.Y') }} bereits <strong>final versiegelt</strong>. 
                Korrekturen werden im System-Log dokumentiert.
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Datum</th>
                        <th>Typ</th>
                        <th>Zeitraum</th>
                        <th>Schüler / Notiz</th>
                        <th class="text-end pe-4">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Filter Logik direkt im Blade (da wir das Collection bereits haben)
                        $gefilterteEintraege = $eintraege;
                        if(request('typ')) {
                            $gefilterteEintraege = $eintraege->where('typ', request('typ'));
                        }
                    @endphp

                    @forelse($gefilterteEintraege as $eintrag)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('d.m.Y') }}</td>
                        <td>
                            @if($eintrag->typ == 'arbeit')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    <i class="bi bi-house-door me-1"></i> Intern
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="bi bi-person-walking me-1"></i> Extern
                                </span>
                            @endif
                        </td>
                        <td class="small">
                            {{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} - {{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }}
                            <span class="text-muted ms-1">({{ number_format(\Carbon\Carbon::parse($eintrag->start_zeit)->diffInMinutes(\Carbon\Carbon::parse($eintrag->ende_zeit)) / 60, 2, ',', '.') }} h)</span>
                        </td>
                        <td>
                            @if($eintrag->schueler)
                                <span class="d-block fw-bold small">{{ $eintrag->schueler->name }}</span>
                            @endif
                            <span class="text-muted small italic">{{ Str::limit($eintrag->notiz, 50) ?: 'Keine Notiz' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('zeiteintraege.edit', $eintrag->id) }}" class="btn btn-sm btn-white border shadow-sm">
                                    <i class="bi bi-pencil text-primary"></i>
                                </a>
                                <form action="{{ route('zeiteintraege.destroy', $eintrag->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white border shadow-sm" onclick="return confirm('Eintrag wirklich löschen?')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted italic">
                            <i class="bi bi-info-circle me-1"></i> Keine Einträge für den Filter "{{ request('typ') ?: 'Alle' }}" gefunden.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .italic { font-style: italic; }
    .btn-white { background: white; }
    .bg-info-subtle { background-color: #e0f2fe; }
    .bg-primary-subtle { background-color: #e0e7ff; }
</style>
@endsection