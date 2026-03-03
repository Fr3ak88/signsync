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

                        <div class="d-flex justify-content-between align-items-center">
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