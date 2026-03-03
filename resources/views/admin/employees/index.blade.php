@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Mitarbeiter-Verzeichnis</h1>
            <p class="text-muted">Verwalten Sie das Personal von <strong>{{ Auth::user()->company }}</strong></p>
        </div>
        @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-6 text-md-end">
            <a href="/admin/employees/create" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Neuer Mitarbeiter
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="/admin/employees" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Nach Position filtern</label>
                    <select name="position" class="form-select">
                        <option value="">Alle Positionen anzeigen</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->name }}" {{ request('position') == $pos->name ? 'selected' : '' }}>
                                {{ $pos->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-filter me-1"></i> Filtern
                    </button>
                    @if(request('position'))
                        <a href="/admin/employees" class="btn btn-link text-decoration-none text-muted">
                            Filter aufheben
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="py-3">Position</th>
                            <th class="py-3">E-Mail</th>
                            <th class="py-3">Hinzugefügt am</th>
                            <th class="px-4 py-3 text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="px-4 fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1">{{ $employee->position }}</span>
                                </td>
                                <td>
                                    @if($employee->email)
                                        <a href="mailto:{{ $employee->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope me-1 small"></i>{{ $employee->email }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Keine E-Mail</span>
                                    @endif
                                </td>
                                <td>{{ $employee->created_at->format('d.m.Y') }}</td>
                                <td class="px-4 text-end">
                                    <div class="btn-group shadow-sm">
                                        <a href="/admin/employees/{{ $employee->id }}/edit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil me-1"></i> Bearbeiten
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $employee->id }}">
                                            <i class="bi bi-trash me-1"></i> Löschen
                                        </button>
                                    </div>

                                    <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $employee->id }}">Bestätigung erforderlich</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    Möchten Sie den Mitarbeiter <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> wirklich aus dem System löschen?
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Abbrechen</button>
                                                    <form action="/admin/employees/{{ $employee->id }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger px-4">Löschen</button>
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
                                    <div class="text-muted">
                                        <i class="bi bi-people display-4 d-block mb-3"></i>
                                        @if(request('position'))
                                            <p>Keine Mitarbeiter mit der Position <strong>{{ request('position') }}</strong> gefunden.</p>
                                            <a href="/admin/employees" class="btn btn-sm btn-outline-secondary">Filter zurücksetzen</a>
                                        @else
                                            <p>Noch keine Mitarbeiter für <strong>{{ Auth::user()->company }}</strong> angelegt.</p>
                                            <a href="/admin/employees/create" class="btn btn-sm btn-primary">Ersten Mitarbeiter hinzufügen</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/dashboard" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Zurück zum Dashboard
        </a>
    </div>
</div>
@endsection