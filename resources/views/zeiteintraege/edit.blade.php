@extends('layouts.app')

@section('content')
<div class="container text-start">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold">Eintrag bearbeiten</h1>
            <p class="text-muted small">
                <i class="bi bi-info-circle me-1"></i> 
                Hinweis: Sie bearbeiten diesen Eintrag als <strong>Administrator</strong>.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.arbeitsnachweise.show', [
                        'user' => $eintrag->user_id, 
                        'month' => \Carbon\Carbon::parse($eintrag->start_zeit)->format('m'), 
                        'year' => \Carbon\Carbon::parse($eintrag->start_zeit)->format('Y')
                    ]) }}" class="btn btn-light border shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Abbrechen
                </a>
            @else
                <a href="{{ route('zeiteintraege.index') }}" class="btn btn-light border shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Abbrechen
                </a>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('zeiteintraege.update', $eintrag->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Schüler Anzeige --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Zugeordneter Schüler</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-mortarboard-fill text-primary"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0 fw-bold" 
                                   value="{{ $eintrag->schueler->name ?? 'Unbekannter Schüler' }}" readonly>
                        </div>
                        <input type="hidden" name="schueler_id" value="{{ $eintrag->schueler_id }}">
                    </div>

                    {{-- Startzeit --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-uppercase">Start-Zeitpunkt</label>
                        <input type="datetime-local" name="start_zeit" 
                               class="form-control @error('start_zeit') is-invalid @enderror" 
                               value="{{ \Carbon\Carbon::parse($eintrag->start_zeit)->format('Y-m-d\TH:i') }}">
                        @error('start_zeit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Endezeit --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-uppercase">Ende-Zeitpunkt</label>
                        <input type="datetime-local" name="ende_zeit" 
                               class="form-control @error('ende_zeit') is-invalid @enderror" 
                               value="{{ \Carbon\Carbon::parse($eintrag->ende_zeit)->format('Y-m-d\TH:i') }}">
                        @error('ende_zeit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Notiz --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold small text-uppercase">Notiz / Kommentar</label>
                        <textarea name="notiz" class="form-control" rows="2" placeholder="Was wurde in dieser Zeit gemacht?">{{ old('notiz', $eintrag->notiz) }}</textarea>
                    </div>

                    <hr class="my-4 text-muted">

                    {{-- GoBD GRUND DER ÄNDERUNG (Nur für Admins sichtbar) --}}
                    @if(auth()->user()->role === 'admin')
                    <div class="col-md-12 mb-4">
                        <div class="p-3 bg-warning-subtle border-start border-warning border-4 rounded shadow-sm">
                            <label class="form-label fw-bold small text-uppercase text-warning-emphasis">
                                <i class="bi bi-journal-text me-1"></i> Grund der manuellen Änderung (GoBD)
                            </label>
                            <select name="change_reason" class="form-select border-warning-subtle mb-2" required>
                                <option value="" selected disabled>Bitte einen Grund wählen...</option>
                                <option value="Korrektur Tippfehler">Korrektur Tippfehler</option>
                                <option value="Nachträgliche Ergänzung durch Mitarbeiter">Nachträgliche Ergänzung durch Mitarbeiter</option>
                                <option value="Abgleich mit Schulbestätigung">Abgleich mit Schulbestätigung</option>
                                <option value="Sonstiges">Sonstiges (siehe Kommentar)</option>
                            </select>
                            <textarea name="change_comment" class="form-control form-control-sm border-warning-subtle" rows="2" placeholder="Zusätzliche Erläuterung (optional)"></textarea>
                            <small class="text-muted d-block mt-2">
                                Diese Angabe wird im Änderungsprotokoll für den Prüfer gespeichert.
                            </small>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Änderungen revisionssicher speichern
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection