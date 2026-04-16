@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>Benutzer-Verzeichnis</h1>
            <p class="text-muted">Hier verwalten Sie alle registrierten User und deren Rollen.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge bg-primary fs-6">{{ $users->count() }} User gesamt</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Name / E-Mail</th>
                            <th>Firma / Institution</th>
                            <th>Rolle</th>
                            <th>Registriert am</th>
                            <th class="text-end pe-4">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary">{{ $user->name }}</div>
                                <small class="text-muted">ID: #{{ $user->id }}</small>
                            </td>
                            <td>
                                @if($user->company)
                                    <span class="fw-bold text-dark">{{ $user->company }}</span>
                                @else
                                    <span class="text-danger small"><i>Keine Zuweisung</i></span>
                                @endif
                            </td>
                            <td>
                                <span class="badge border text-dark bg-light fw-normal">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
        {{-- NEU: Link erneut senden Button --}}
        <form action="{{ route('superadmin.users.resend', $user->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary" title="Einladungs-Link erneut senden">
                <i class="bi bi-envelope-at"></i>
            </button>
        </form>

        <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary" title="Bearbeiten">
            <i class="bi bi-pencil"></i>
        </a>
        
        @if($user->id != 1)
            {{-- Lösch-Button öffnet das Modal --}}
            <button type="button" 
                    class="btn btn-sm btn-outline-danger" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal{{ $user->id }}"
                    title="Löschen">
                <i class="bi bi-trash"></i>
            </button>
        @else
            {{-- Schutz-Icon für ID 1 --}}
            <button class="btn btn-sm btn-light border text-muted" disabled title="System-Admin geschützt">
                <i class="bi bi-lock-fill"></i>
            </button>
        @endif
    </div>

                                {{-- MODAL OVERLAY FÜR DIESEN USER --}}
                                @if($user->id != 1)
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Löschvorgang bestätigen</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start p-4">
                                                Soll der Benutzer <strong>{{ $user->name }}</strong> wirklich gelöscht werden? 
                                                <p class="text-danger small mt-2 mb-0"><strong>Achtung:</strong> Alle zugehörigen Daten wie Zeiteinträge und Verknüpfungen werden unwiderruflich entfernt!</p>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Unwiderruflich löschen</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                {{-- ENDE MODAL --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('superadmin.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Zurück zur Zentrale
        </a>
    </div>
</div>
@endsection