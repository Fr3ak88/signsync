@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Stammdaten: Positionen/Rollen</h1>
            <p class="text-muted">Definieren Sie die Rollen für Ihre Mitarbeiter von <strong>{{ Auth::user()->company }}</strong></p>
        </div>
            <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i> Zurück zum Dashboard
        </a>
    </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Neue Position erstellen</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/positions" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Bezeichnung</label>
                            <input type="text" name="name" class="form-control" placeholder="z.B. Schulbegleiter" required>
                            <div class="form-text">Diese Bezeichnung erscheint später im Dropdown beim Mitarbeiter.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Vorhandene Positionen</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Bezeichnung</th>
                                    <th class="py-3">Erstellt am</th>
                                    <th class="px-4 py-3 text-end">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($positions as $pos)
                                    <tr>
                                        <td class="px-4 fw-bold">{{ $pos->name }}</td>
                                        <td>{{ $pos->created_at->format('d.m.Y') }}</td>
                                        <td class="px-4 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModalPos{{ $pos->id }}">
                                                <i class="bi bi-trash me-1"></i> Löschen
                                            </button>

                                            <div class="modal fade" id="deleteModalPos{{ $pos->id }}" tabindex="-1" aria-labelledby="deleteModalLabelPos{{ $pos->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-0 text-start">
                                                            <h5 class="modal-title fw-bold" id="deleteModalLabelPos{{ $pos->id }}">Sicherheitshinweis</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            Möchten Sie die Position <strong>{{ $pos->name }}</strong> wirklich löschen?
                                                            <p class="text-muted small mt-2">Hinweis: Mitarbeiter, denen diese Position bereits zugewiesen wurde, behalten den Text. Die Position kann jedoch nicht mehr für neue Zuweisungen ausgewählt werden.</p>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Abbrechen</button>
                                                            <form action="/admin/positions/{{ $pos->id }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger px-4">Endgültig löschen</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-tags display-4 d-block mb-3"></i>
                                            Keine Positionen definiert.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection