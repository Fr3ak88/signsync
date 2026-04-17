@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="/zeiteintraege" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left"></i> Zurück zum Dashboard
        </a>
    </div>

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
                <div class="col-md-4">
                    <p class="text-muted mb-1">Startzeit</p>
                    <p class="fw-bold">{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('H:i') }} Uhr</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Endzeit</p>
                    <p class="fw-bold">{{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('H:i') }} Uhr</p>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Dauer</p>
                    <p class="fw-bold text-success">
                        {{ \Carbon\Carbon::parse($eintrag->start_zeit)->diffInMinutes($eintrag->ende_zeit) }} Min.
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
        <div class="card-footer bg-white py-3">
            <form action="/zeiteintraege/{{ $eintrag->id }}" method="POST" onsubmit="return confirm('Eintrag wirklich löschen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Eintrag löschen
                </button>
            </form>
        </div>
    </div>
</div>
@endsection