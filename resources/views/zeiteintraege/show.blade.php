@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h5 class="mb-0 fw-bold text-primary">Eintragsdetails #{{ $eintrag->id }}</h5>
            <span class="badge bg-light text-muted border">Erstellt am {{ $eintrag->created_at->format('d.m.Y H:i') }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Schüler</p>
                    <p class="fw-bold fs-5">{{ $eintrag->schueler->name ?? 'Interner Eintrag'}}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-1">Datum</p>
                    <p class="fw-bold fs-5">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('d.m.Y') }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <p class="text-muted mb-1">Startzeit</p>
                    <p class="fw-bold">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} Uhr</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Endzeit</p>
                    <p class="fw-bold">{{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }} Uhr</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Pause</p>
                    <p class="fw-bold text-warning">{{ $eintrag->pause ?? 0 }} Min.</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Dauer (Netto)</p>
                    <p class="fw-bold text-success">
                        @php
                            $start = \Carbon\Carbon::parse($eintrag->start_zeit);
                            $ende = \Carbon\Carbon::parse($eintrag->ende_zeit);
                            $pausenMinuten = $eintrag->pause ?? 0;
                            
                            // Brutto-Minuten minus Pause = Netto-Stunden
                            $nettoMinuten = $start->diffInMinutes($ende) - $pausenMinuten;
                            $stundenDezimal = max(0, $nettoMinuten / 60); // max(0, ...) verhindert negative Werte
                        @endphp
                        {{ number_format($stundenDezimal, 2, ',', '.') }} Std.
                    </p>
                </div>
            </div>
            @if($eintrag->notiz)
                <div class="mt-4 p-3 bg-light rounded">
                    <p class="text-muted small mb-1">Bemerkungen:</p>
                    <p class="mb-0">{{ $eintrag->notiz }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-end">
            <form action="/zeiteintraege/{{ $eintrag->id }}" method="POST" onsubmit="return confirm('Eintrag wirklich löschen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Eintrag löschen
                </button>
            </form>
        </div>
        <div class="mb-3 d-flex justify-content-end">
            <a href="/zeiteintraege" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Zurück
            </a>
        </div>
    </div>
</div>
@endsection