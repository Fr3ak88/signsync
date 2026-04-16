@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="/admin/employees" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Zurück zur Liste
                </a>
                
                {{-- Anzeige der aktuellen Auslastung --}}
                <span class="badge {{ Auth::user()->employees()->count() >= Auth::user()->max_employees ? 'bg-danger' : 'bg-light text-dark border' }}">
                    Plätze: {{ Auth::user()->employees()->count() }} / {{ Auth::user()->max_employees }}
                </span>
            </div>

            {{-- START: LIMIT-FEHLERMELDUNG --}}
            @if(Auth::user()->employees()->count() >= Auth::user()->max_employees)
                <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-1">Mitarbeiter-Limit erreicht!</h6>
                        In Ihrem aktuellen Paket (**{{ ucfirst(Auth::user()->plan_name) }}**) können Sie maximal 
                        {{ Auth::user()->max_employees }} Mitarbeiter verwalten. Bitte führen Sie ein 
                        <a href="{{ route('plans.index') }}" class="alert-link">Upgrade</a> durch oder löschen Sie einen alten Zugang.
                    </div>
                </div>
            @endif
            {{-- ENDE: LIMIT-FEHLERMELDUNG --}}

            <div class="card border-0 shadow-sm {{ Auth::user()->employees()->count() >= Auth::user()->max_employees ? 'opacity-75' : '' }}">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">Neuen Mitarbeiter anlegen</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="/admin/employees">
                        @csrf

                        {{-- Formular-Felder (Inaktiv wenn Limit erreicht) --}}
                        <fieldset {{ Auth::user()->employees()->count() >= Auth::user()->max_employees ? 'disabled' : '' }}>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-bold">Vorname</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required autofocus>
                                    @error('first_name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-bold">Nachname</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">E-Mail-Adresse</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="beispiel@firma.de">
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="position" class="form-label fw-bold">Position / Rolle</label>
                                <select name="position" id="position" class="form-select @error('position') is-invalid @enderror" required>
                                    <option value="" selected disabled>Bitte wählen...</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->name }}" {{ old('position') == $pos->name ? 'selected' : '' }}>
                                            {{ $pos->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Schüler-Zuweisung --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">
                                    <i class="bi bi-person-check me-1"></i> Schüler zuweisen
                                </label>
                                <div class="p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                    @forelse($schueler as $kind)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="schueler[]" value="{{ $kind->id }}" id="kind{{ $kind->id }}" {{ is_array(old('schueler')) && in_array($kind->id, old('schueler')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="kind{{ $kind->id }}">
                                                <span class="fw-bold">{{ $kind->name }}</span> 
                                                <span class="text-muted small">({{ $kind->school_name ?? 'Keine Schule' }})</span>
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">Keine Schüler gefunden.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm" {{ Auth::user()->employees()->count() >= Auth::user()->max_employees ? 'disabled' : '' }}>
                                    <i class="bi bi-person-check me-2"></i>Mitarbeiter speichern & Einladung senden
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection