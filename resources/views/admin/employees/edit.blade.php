@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">Mitarbeiter bearbeiten</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/employees/{{ $employee->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vorname</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nachname</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $employee->last_name }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">E-Mail (optional)</label>
                            <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Position / Rolle</label>
                            <select name="position" class="form-select @error('position') is-invalid @enderror" required>
                                <option value="" disabled>Bitte wählen...</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->name }}" {{ $employee->position == $pos->name ? 'selected' : '' }}>
                                        {{ $pos->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($positions->isEmpty())
                                <div class="form-text text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i> 
                                    Keine Positionen angelegt. <a href="/admin/positions">Hier Positionen erstellen.</a>
                                </div>
                            @endif
                        </div>

                        {{-- NEU: BEREICH FÜR SCHÜLER-ZUWEISUNG --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">
                                <i class="bi bi-person-check me-1"></i> Zugewiesene Schüler
                            </label>
                            <div class="p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                                @forelse($schueler as $kind)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="schueler[]" 
                                               value="{{ $kind->id }}" 
                                               id="kind{{ $kind->id }}"
                                               {{ $employee->schueler->contains($kind->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kind{{ $kind->id }}">
                                            <span class="fw-bold">{{ $kind->name }}</span> 
                                            <span class="text-muted small">({{ $kind->school_name ?? 'Keine Schule angegeben' }})</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0 italic">
                                        <i class="bi bi-info-circle me-1"></i> Keine Schüler in der Datenbank gefunden.
                                    </p>
                                @endforelse
                            </div>
                            <div class="form-text mt-2">
                                Wählen Sie aus, welche Schüler dieser Mitarbeiter im Zeiteintrag sehen darf.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/admin/employees" class="text-decoration-none text-muted">
                                <i class="bi bi-x-circle me-1"></i> Abbrechen
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Änderungen speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection