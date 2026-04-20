@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="{{ route('admin.schueler.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Zurück zur Übersicht
                </a>
                <h2 class="fw-bold mt-3">Neuen Schüler anlegen</h2>
                <p class="text-muted">Fügen Sie einen neuen Klienten für <strong>{{ Auth::user()->company }}</strong> hinzu.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.schueler.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Vollständiger Name des Schülers</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="z.B. Max Mustermann" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label fw-bold">Geburtsdatum<small class="text-muted fw-normal" style="font-size: 0.75rem; vertical-align: top;">(optional)</small></label>
                                <input type="date" 
                                       name="birth_date" 
                                       id="birth_date" 
                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                       value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="school_name" class="form-label fw-bold">Schule / Einrichtung</label>
                                <input type="text" 
                                       name="school_name" 
                                       id="school_name" 
                                       class="form-control @error('school_name') is-invalid @enderror" 
                                       placeholder="z.B. Waldschule"
                                       value="{{ old('school_name') }}">
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.schueler.index') }}" class="btn btn-light border px-4">Abbrechen</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Schüler speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm mt-4 d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Datenschutz:</strong> Alle personenbezogenen Daten werden nach aktuellen Sicherheitsstandards verschlüsselt.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection