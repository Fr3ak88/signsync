@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Header-Bereich --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold">Schüler-Datenbank</h1>
            <p class="text-muted">Verwalten Sie die Klienten für <strong>{{ Auth::user()->company }}</strong></p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.schueler.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Neuen Schüler anlegen
            </a>
        </div>
    </div>

    {{-- Erfolgsmeldungen --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Hauptkarte mit Tabelle --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="py-3">Schule</th>
                            <th class="py-3">Geburtsdatum</th>
                            <th class="px-4 py-3 text-end">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schueler as $einzelner)
                            <tr>
                                <td class="px-4 fw-bold">
                                    <i class="bi bi-shield-lock text-success me-2" title="DSGVO verschlüsselt"></i>
                                    {{ $einzelner->name }}
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $einzelner->school_name ?? 'Nicht angegeben' }}</span>
                                </td>
                                <td>
                                    {{ $einzelner->birth_date ? $einzelner->birth_date->format('d.m.Y') : '-' }}
                                </td>
                                <td class="px-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                         <a href="{{ route('admin.schueler.edit', $einzelner->id) }}" 
                                             class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                            <i class="bi bi-pencil me-1"></i> Bearbeiten
                                        </a>

                                        <button type="button" 
                                            class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $einzelner->id }}">
                                            <i class="bi bi-trash me-1"></i> Löschen
                                        </button>
                                    </div>

                                    {{-- Lösch-Bestätigung Modal --}}
                                    <div class="modal fade" id="deleteModal{{ $einzelner->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-bottom-0">
                                                    <h5 class="modal-title fw-bold">Schüler löschen?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start py-3">
                                                    Sind Sie sicher, dass Sie <strong>{{ $einzelner->name }}</strong> löschen möchten? <br>
                                                    <span class="text-danger small"><i class="bi bi-exclamation-triangle"></i> Alle verknüpften Zeiteinträge werden ebenfalls gelöscht.</span>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Abbrechen</button>
                                                    <form action="{{ route('admin.schueler.destroy', $einzelner->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger px-4">Unwiderruflich löschen</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-mortarboard display-4 d-block mb-3"></i>
                                    Keine Schüler für <strong>{{ Auth::user()->company }}</strong> gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Navigations-Buttons --}}
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i> Zurück zum Dashboard
        </a>
    </div>
</div>
@endsection