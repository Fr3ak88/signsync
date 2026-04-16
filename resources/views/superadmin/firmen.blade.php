@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 1. HEADER BEREICH --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold text-dark"><i class="bi bi-building me-2"></i>Firmen-Verzeichnis</h1>
            <p class="text-muted">Hier verwalten Sie alle registrierten Mandanten (Admins).</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge bg-primary fs-6">{{ $firmen->count() }} Mandanten gesamt</span>
        </div>
    </div>

    {{-- 2. ALERTS --}}
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

    {{-- 3. TABELLEN CARD --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Firma / Institution</th>
                            <th>Admin-Name</th>
                            <th>E-Mail</th>
                            <th>Registriert am</th>
                            <th class="text-end pe-4">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($firmen as $firma)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary">{{ $firma->company ?? 'Keine Angabe' }}</div>
                                <small class="text-muted">ID: #{{ $firma->id }}</small>
                            </td>
                            <td>{{ $firma->name }}</td>
                            <td>
                                <a href="mailto:{{ $firma->email }}" class="text-decoration-none">
                                    {{ $firma->email }}
                                </a>
                            </td>
                            <td>{{ $firma->created_at->format('d.m.Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('superadmin.users.edit', $firma->id) }}" class="btn btn-sm btn-outline-secondary" title="Bearbeiten">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteFirmaModal{{ $firma->id }}"
                                            title="Löschen">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- MODAL --}}
                                <div class="modal fade" id="deleteFirmaModal{{ $firma->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><i class="bi bi-exclamation-octagon me-2"></i>Firma löschen?</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start p-4 text-wrap">
                                                Sie löschen gerade <strong>{{ $firma->company ?? $firma->name }}</strong>.
                                                <div class="alert alert-warning mt-3 mb-0">
                                                    <ul class="small mb-0">
                                                        <li>Admin-Account</li>
                                                        <li>Mitarbeiter & Schüler</li>
                                                        <li>Alle Zeiteinträge</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                                <form action="{{ route('superadmin.firmen.destroy', $firma->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Unwiderruflich löschen</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted">Keine Firmen vorhanden.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> {{-- Hier fehlte das schließende Div für die Card --}}

    {{-- 4. FOOTER BUTTON --}}
    <div class="mt-4">
        <a href="{{ route('superadmin.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Zurück zur Zentrale
        </a>
    </div>
</div>
@endsection