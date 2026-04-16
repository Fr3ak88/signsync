@extends('layouts.app')

@section('content')
<div class="container text-start py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
    @php
        $previousUrl = url()->previous();
        $backUrl = route('dashboard'); // Fallback Standard

        if (str_contains($previousUrl, 'zeiteintraege')) {
            $backUrl = route('zeiteintraege.index');
        } elseif (str_contains($previousUrl, 'worktime')) {
            $backUrl = route('worktime.index');
        }
    @endphp

    <a href="{{ $backUrl }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left"></i> 
        Zurück zu {{ str_contains($backUrl, 'dashboard') ? 'Übersicht' : 'meinen Einträgen' }}
    </a>
</div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-success"></i>Neuen Zeiteintrag erstellen</h5>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('zeiteintraege.store') }}">
                        @csrf
                        
                        {{-- Wichtig: Typ wird als 'arbeit' mitgesendet --}}
                        <input type="hidden" name="typ" value="arbeit">
                        {{-- schueler_id bleibt bei interner Arbeit null --}}
                        <input type="hidden" name="schueler_id" value="">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Art der Erfassung</label>
                            <div class="alert alert-info border-0 shadow-sm bg-light-subtle mb-0">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-info me-3 fs-4"></i>
                                    <div>
                                        <strong class="d-block">Büro / Organisation / Fortbildung</strong>
                                        <span class="small text-muted">Diese Stunden werden für den internen Arbeitsnachweis (Büro) erfasst und erfordern keine Schülerzuordnung.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Start (Datum/Uhrzeit)</label>
                                <input type="datetime-local" name="start_zeit" 
                                       class="form-control @error('start_zeit') is-invalid @enderror" 
                                       value="{{ old('start_zeit', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('start_zeit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Ende (Datum/Uhrzeit)</label>
                                <input type="datetime-local" name="ende_zeit" 
                                       class="form-control @error('ende_zeit') is-invalid @enderror" 
                                       value="{{ old('ende_zeit', now()->addHours(1)->format('Y-m-d\TH:i')) }}" required>
                                @error('ende_zeit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tätigkeitsbeschreibung</label>
                            <textarea name="notiz" class="form-control @error('notiz') is-invalid @enderror" 
                                      rows="3" placeholder="z.B. Dokumentation, Team-Meeting, Fahrtzeit...">{{ old('notiz') }}</textarea>
                            @error('notiz')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-info py-2 fw-bold text-white shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Arbeitszeit speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection