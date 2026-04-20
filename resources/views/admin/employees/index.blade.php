@extends('layouts.app')

@section('content')
<div class="container pb-5"> {{-- pb-5 für etwas Platz nach dem unteren Button --}}
    {{-- Kleine Navigation oben --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mitarbeiterverwaltung</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">Mitarbeiterverwaltung</h2>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Neuer Mitarbeiter
        </a>
    </div>

    {{-- TABS FÜR STATUS-FILTER --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $status !== 'archiv' ? 'active fw-bold' : '' }}" 
               href="{{ route('admin.employees.index') }}">
                <i class="bi bi-person-check me-1"></i> Aktiv
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'archiv' ? 'active fw-bold text-danger' : 'text-muted' }}" 
               href="{{ route('admin.employees.index', ['status' => 'archiv']) }}">
                <i class="bi bi-archive me-1"></i> Archiv (Inaktiv)
            </a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>E-Mail</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $employee->user->name ?? 'N/A' }}</div>
                                </td>
                                <td class="text-muted small">{{ $employee->user->email ?? 'N/A' }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>
                                    @if($employee->trashed())
                                        <span class="badge bg-secondary-subtle text-secondary border">
                                            Inaktiv ({{ $employee->deleted_at->format('d.m.Y') }})
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border">Aktiv</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($employee->trashed())
                                        <form action="{{ route('admin.employees.restore', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-counterclockwise"></i> Reaktivieren
                                            </button>
                                        </form>
                                    @else
                                        <div class="btn-group">
                                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-secondary" title="Bearbeiten">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-employee"
                                                    title="Deaktivieren"
                                                    data-employee-name="{{ $employee->user->name ?? 'Diesen Mitarbeiter' }}">
                                                    <i class="bi bi-person-x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic">
                                    <i class="bi bi-info-circle d-block mb-2 fs-4"></i>
                                    Keine Mitarbeiter in dieser Ansicht gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ZURÜCK BUTTON AM ENDE DER LISTE --}}
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i> Zurück zum Dashboard
        </a>
    </div>
</div>
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger" id="deleteEmployeeModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Mitarbeiter deaktivieren
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2 fw-semibold">Möchtest du <span id="deleteEmployeeName">diesen Mitarbeiter</span> wirklich deaktivieren?</p>
                <p class="text-muted small mb-0">
                    Der Login wird gesperrt, vorhandene Zeiteinträge bleiben erhalten.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Abbrechen</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteEmployee">
                    <i class="bi bi-person-x me-1"></i>Ja, deaktivieren
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedForm = null;
        const modalElement = document.getElementById('deleteEmployeeModal');
        const deleteModal = new bootstrap.Modal(modalElement);
        const employeeNameEl = document.getElementById('deleteEmployeeName');
        const confirmBtn = document.getElementById('confirmDeleteEmployee');

        document.querySelectorAll('.btn-delete-employee').forEach(button => {
            button.addEventListener('click', function () {
                selectedForm = this.closest('form');
                employeeNameEl.textContent = this.dataset.employeeName || 'diesen Mitarbeiter';
                deleteModal.show();
            });
        });

        confirmBtn.addEventListener('click', function () {
            if (selectedForm) {
                selectedForm.submit();
            }
        });
    });
</script>
@endsection