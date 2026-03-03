@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Mitarbeiter-Verwaltung</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    + Neuer Mitarbeiter
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Vorname</th>
                                <th>Nachname</th>
                                <th>E-Mail</th>
                                <th>Rolle</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->first_name }}</td>
                                    <td>{{ $employee->last_name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $employee->role }}</span></td>
                                    <td><span class="badge bg-success">Aktiv</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Noch keine Mitarbeiter angelegt.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.employees.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Neuen Mitarbeiter anlegen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Vorname</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Nachname</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail Adresse</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort (Initial)</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                        <small class="text-muted">Das Passwort, mit dem sich der Mitarbeiter zum ersten Mal anmeldet.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection