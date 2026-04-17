@extends('layouts.app')

@section('content')
<div class="container text-start">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Zeiteinträge</h1>
            <p class="text-muted">Hier sehen Sie alle erfassten Zeiträume für Ihre Schüler.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('zeiteintraege.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Zeitraum erfassen
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Datum</th>
                            <th class="py-3">Mitarbeiter</th>
                            <th class="py-3">Schüler</th>
                            <th class="py-3">Zeitraum</th>
                            <th class="py-3 text-center">Dauer</th>
                            <th class="px-4 py-3 text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eintraege as $eintrag)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('d.m.Y') }}</div>
                                </td>
                                <td>{{ $eintrag->user->name ?? 'System' }}</td>
                                <td>
                                    <span class="badge border text-dark fw-normal">
                                        <i class="bi bi-mortarboard me-1"></i> {{ $eintrag->schueler->name ?? 'Interner Eintrag' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }} Uhr
                                    </small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $start = \Carbon\Carbon::parse($eintrag->start_zeit);
                                        $ende = \Carbon\Carbon::parse($eintrag->ende_zeit);
                                        $stunden = $start->diffInMinutes($ende) / 60;
                                    @endphp
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-light text-primary border border-primary-subtle">
                                            {{ number_format($stunden, 2, ',', '.') }} Std.
                                        </span>
                                        
                                        {{-- Hinweis bei Bearbeitung --}}
                                        @if($eintrag->was_edited)
                                            <span class="badge bg-warning text-dark mt-1 shadow-sm" style="font-size: 0.7rem;" title="Dieser Eintrag wurde nachträglich geändert">
                                                <i class="bi bi-pencil-square me-1"></i> Bearbeitet
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 text-end">
                                    <div class="btn-group shadow-sm">
                                        {{-- Details immer sichtbar --}}
                                        <a href="{{ route('zeiteintraege.show', $eintrag->id) }}" class="btn btn-sm btn-outline-secondary" title="Details ansehen">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Bearbeiten und Löschen nur für den Besitzer des Eintrags --}}
                                        @if($eintrag->user_id === Auth::id())
                                            <a href="{{ route('zeiteintraege.edit', $eintrag->id) }}" class="btn btn-sm btn-outline-primary" title="Bearbeiten">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <form action="{{ route('zeiteintraege.destroy', $eintrag->id) }}" method="POST" onsubmit="return confirm('Möchten Sie diesen Eintrag wirklich unwiderruflich löschen?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Löschen">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-clock-history display-4 d-block mb-3 text-muted"></i>
                                    <p class="text-muted">Noch keine Zeiteinträge vorhanden.</p>
                                    <a href="{{ route('zeiteintraege.create') }}" class="btn btn-sm btn-primary">Ersten Eintrag erstellen</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $eintraege->links() }}
    </div>
</div>
@endsection