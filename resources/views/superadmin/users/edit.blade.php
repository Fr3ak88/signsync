@extends('layouts.app')

@section('content')
<div class="container text-start">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>User bearbeiten</h1>
            <p class="text-muted">Hier können Sie alle Stammdaten des Benutzers ID #{{ $user->id }} ändern.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- SEKTION 1: STAMMDATEN --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Benutzerdaten anpassen</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">E-Mail Adresse</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">System-Rolle</label>
                                <select name="role" class="form-select">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Firmenchef)</option>
                                    <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee (Mitarbeiter)</option>
                                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-primary">Unternehmen (Firma)</label>
                                <input class="form-control border-primary" name="company" list="firmenOptions" id="companyDataList" value="{{ old('company', $user->company) }}" placeholder="Firma suchen oder neu eingeben...">
                                <datalist id="firmenOptions">
                                    @foreach($firmenListe as $f)
                                        <option value="{{ $f }}">
                                    @endforeach
                                </datalist>
                                <small class="text-muted">Wählen Sie eine Firma oder tippen Sie eine neue ein.</small>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i>Änderungen speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SEKTION 2: NEU - ABO-PLAN MANUELL ÄNDERN --}}
            @if($user->role === 'admin')
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-credit-card-2-back me-2"></i>Abonnement & Limits</h5>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-4">
                        Hier können Sie den Abo-Status manuell überschreiben. Dies ist nützlich, wenn der Kunde per Überweisung zahlt oder Sie den Account manuell freischalten möchten.
                    </p>
                    
                    <form action="{{ route('superadmin.users.updatePlan', $user->id) }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Aktuelles Paket wählen</label>
                                <select name="plan" class="form-select border-warning">
                                    <option value="none" {{ is_null($user->plan_name) ? 'selected' : '' }}>Kein aktives Abo (Gesperrt)</option>
                                    <option value="starter" {{ $user->plan_name == 'starter' ? 'selected' : '' }}>Starter Paket (5 Mitarbeiter)</option>
                                    <option value="team" {{ $user->plan_name == 'team' ? 'selected' : '' }}>Team Paket (20 Mitarbeiter)</option>
                                    <option value="pro" {{ $user->plan_name == 'pro' ? 'selected' : '' }}>Pro Paket (50+)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-warning w-100 fw-bold text-white shadow-sm">
                                    Plan setzen
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <span class="badge {{ $user->has_active_subscription ? 'bg-success' : 'bg-danger' }}">
                            Status: {{ $user->has_active_subscription ? 'Aktiv' : 'Inaktiv' }}
                        </span>
                        <span class="badge bg-secondary ms-2">
                            Limit: {{ $user->max_employees }} Mitarbeiter
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-light border shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Zurück zur Liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection