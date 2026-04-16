@extends('layouts.app')

@section('content')
<div class="container text-start">
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
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('zeiteintraege.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Schüler / Klient</label>
                            
                            @if($schueler && $schueler->count() === 1)
                                {{-- Fall 1: Nur ein Schüler zugewiesen --}}
                                @php $s = $schueler->first(); @endphp
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-mortarboard-fill text-success"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light border-start-0 fw-bold" 
                                           value="{{ $s->name }}" readonly disabled>
                                </div>
                                <p class="text-muted small mt-1">Eintrag erfolgt für: <strong>{{ $s->name }}</strong></p>
                                
                                {{-- Verstecktes Feld für die ID, damit der Request sie erhält --}}
                                <input type="hidden" name="schueler_id" value="{{ $s->id }}">

                            @elseif($schueler && $schueler->count() > 1)
                                {{-- Fall 2: Mehrere Schüler zur Auswahl --}}
                                <select name="schueler_id" class="form-select @error('schueler_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Bitte Schüler wählen...</option>
                                    @foreach($schueler as $s)
                                        <option value="{{ $s->id }}" {{ old('schueler_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{-- Fall 3: Gar kein Schüler zugewiesen --}}
                                <div class="alert alert-warning border-0 shadow-sm small">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                                    <strong>Hinweis:</strong> Dir wurde noch kein Schüler zugewiesen oder dein Mitarbeiter-Profil ist unvollständig. 
                                    Bitte kontaktiere die Verwaltung.
                                </div>
                            @endif

                            @error('schueler_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Start (Datum/Uhrzeit)</label>
                                <input type="datetime-local" name="start_zeit" class="form-control @error('start_zeit') is-invalid @enderror" value="{{ old('start_zeit', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('start_zeit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Ende (Datum/Uhrzeit)</label>
                                <input type="datetime-local" name="ende_zeit" class="form-control @error('ende_zeit') is-invalid @enderror" value="{{ old('ende_zeit', now()->addHours(1)->format('Y-m-d\TH:i')) }}" required>
                                @error('ende_zeit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Bemerkung (Optional)</label>
                            <textarea name="notiz" class="form-control" rows="2" placeholder="Besonderheiten während des Einsatzes...">{{ old('notiz') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success py-2 fw-bold shadow-sm" @if(!$schueler || $schueler->isEmpty()) disabled @endif>
                                <i class="bi bi-check-circle me-1"></i> Zeitraum speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection