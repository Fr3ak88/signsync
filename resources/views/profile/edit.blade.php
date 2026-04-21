@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary mb-4">Profileinstellungen</h2>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Persönliche Daten --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Persönliche Informationen</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">E-Mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Neues Passwort (leer lassen für keine Änderung)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Passwort bestätigen</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Firmendaten (Nur für Admins sichtbar) --}}
                @if($user->role === 'admin')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold text-success">Firmendaten & Adresse (für AVV/Rechnungen)</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Firma / Institution</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $user->company) }}">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <label class="form-label small text-muted">Straße</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street', $user->street) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Hausnummer</label>
                                <input type="text" name="house_number" class="form-control" value="{{ old('house_number', $user->house_number) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">PLZ</label>
                                <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $user->zip_code) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small text-muted">Stadt</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ url('/dashboard') }}" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left"></i> Zurück
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i> Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection