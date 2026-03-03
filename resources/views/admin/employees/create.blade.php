@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
                <a href="/admin/employees" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Zurück zur Liste
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">Neuen Mitarbeiter anlegen</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="/admin/employees">
                        @csrf

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

                            @if($positions->isEmpty())
                                <div class="form-text text-danger mt-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i> 
                                    Sie haben noch keine Positionen angelegt. 
                                    <a href="/admin/positions" class="fw-bold text-decoration-none border-bottom border-danger">Jetzt Positionen definieren</a>
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                <i class="bi bi-person-check me-2"></i>Mitarbeiter speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection