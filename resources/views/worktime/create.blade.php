@extends('layouts.app')

@section('content')
<div class="container text-start py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
    @php
        $previousUrl = url()->previous();
        $backUrl = route('dashboard'); // Fallback Standard

        if (str_contains($previousUrl, 'zeiteintraege')) {
            $backUrl = route('zeiteintraege.index');
        } elseif (str_contains($previousUrl, 'worktime')) {
            $backUrl = route('worktime.index');
        }
    @endphp

    <a href="{{ $backUrl }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left"></i> 
        Zurück zu {{ str_contains($backUrl, 'dashboard') ? 'Übersicht' : 'meinen Einträgen' }}
    </a>
</div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-success"></i>Neuen Zeiteintrag erstellen</h5>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('worktime.store') }}">
                        @csrf

                        {{-- Wichtig: Typ wird als 'arbeit' mitgesendet --}}
                        <input type="hidden" name="typ" value="arbeit">
                        {{-- schueler_id bleibt bei interner Arbeit null --}}
                        <input type="hidden" name="schueler_id" value="">
                        <input type="hidden" name="start_zeit" id="start_zeit" value="">
                        <input type="hidden" name="ende_zeit" id="ende_zeit" value="">
                        <input type="hidden" name="pause_minuten" id="pause_minuten" value="0">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Art der Erfassung</label>
                            <div class="alert alert-info border-0 shadow-sm bg-light-subtle mb-0">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-info me-3 fs-4"></i>
                                    <div>
                                        <strong class="d-block">Büro / Organisation / Fortbildung</strong>
                                        <span class="small text-muted">Diese Stunden werden für den internen Arbeitsnachweis (Büro) erfasst und erfordern keine Schülerzuordnung.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Zeiterfassung</label>
                            
                            <!-- Status-Anzeige -->
                            <div class="text-center mb-3">
                                <div id="timer-status" class="badge bg-secondary fs-6 px-3 py-2">
                                    <i class="bi bi-circle-fill me-2"></i>Bereit zum Start
                                </div>
                            </div>
                            
                            <!-- Button-Gruppe zentriert -->
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <button type="button" id="timer-start-btn" class="btn btn-success btn-lg px-4 py-3 fw-bold shadow-lg">
                                    <i class="bi bi-play-circle-fill fs-4 me-2"></i>
                                    <span class="d-block">Start</span>
                                </button>
                                <button type="button" id="timer-pause-btn" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow-lg" disabled>
                                    <i class="bi bi-pause-circle-fill fs-4 me-2"></i>
                                    <span class="d-block">Pause</span>
                                </button>
                                <button type="button" id="timer-stop-btn" class="btn btn-danger btn-lg px-4 py-3 fw-bold shadow-lg" disabled>
                                    <i class="bi bi-stop-circle-fill fs-4 me-2"></i>
                                    <span class="d-block">Stop</span>
                                </button>
                            </div>

                            <!-- Anzeigen in einer Karte -->
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="row g-3 text-center">
                                        <div class="col-md-4">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <i class="bi bi-play-circle text-success fs-3 mb-2"></i>
                                                <label class="form-label small text-uppercase text-muted fw-bold mb-1">Gestartet</label>
                                                <input type="text" id="display-start" class="form-control bg-white border-0 text-center fw-bold" value="Noch nicht gestartet" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <i class="bi bi-stop-circle text-danger fs-3 mb-2"></i>
                                                <label class="form-label small text-uppercase text-muted fw-bold mb-1">Beendet</label>
                                                <input type="text" id="display-end" class="form-control bg-white border-0 text-center fw-bold" value="Noch nicht gestoppt" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 bg-white rounded shadow-sm">
                                                <i class="bi bi-pause-circle text-warning fs-3 mb-2"></i>
                                                <label class="form-label small text-uppercase text-muted fw-bold mb-1">Pause</label>
                                                <input type="text" id="display-pause" class="form-control bg-white border-0 text-center fw-bold" value="0 Min" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Laufzeit-Anzeige prominent -->
                                    <div class="mt-4 text-center">
                                        <div class="p-3 bg-primary text-white rounded shadow-sm">
                                            <i class="bi bi-clock-history fs-4 mb-2"></i>
                                            <label class="form-label text-white fw-bold mb-1">GESAMTLAUFZEIT</label>
                                            <input type="text" id="display-duration" class="form-control bg-primary text-white border-0 text-center fw-bold fs-5" value="-" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('start_zeit')
                                <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                            @enderror
                            @error('ende_zeit')
                                <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tätigkeitsbeschreibung</label>
                            <textarea name="taetigkeit" class="form-control @error('taetigkeit') is-invalid @enderror" 
                                      rows="3" placeholder="z.B. Dokumentation, Team-Meeting, Fahrtzeit...">{{ old('taetigkeit') }}</textarea>
                            @error('taetigkeit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="save-button" class="btn btn-info py-2 fw-bold text-white shadow-sm" disabled>
                                <i class="bi bi-check-circle me-1"></i> Arbeitszeit speichern
                            </button>
                        </div>
                    </form>

                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const startBtn = document.getElementById('timer-start-btn');
                                const pauseBtn = document.getElementById('timer-pause-btn');
                                const stopBtn = document.getElementById('timer-stop-btn');
                                const saveBtn = document.getElementById('save-button');
                                const startInput = document.getElementById('start_zeit');
                                const endInput = document.getElementById('ende_zeit');
                                const pauseInput = document.getElementById('pause_minuten');
                                const displayStart = document.getElementById('display-start');
                                const displayEnd = document.getElementById('display-end');
                                const displayPause = document.getElementById('display-pause');
                                const displayDuration = document.getElementById('display-duration');
                                const statusBadge = document.getElementById('timer-status');

                                let pauseStart = null;
                                let isPaused = false;
                                let timerInterval = null;
                                let heartbeatInterval = null;

                                function updateStatus(status, icon, color) {
                                    statusBadge.innerHTML = `<i class="bi ${icon} me-2"></i>${status}`;
                                    statusBadge.className = `badge bg-${color} fs-6 px-3 py-2`;
                                }

                                function formatLocal(value) {
                                    const date = new Date(value);
                                    return date.toLocaleString('de-DE', {
                                        year: 'numeric',
                                        month: '2-digit',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }).replace(',', '');
                                }

                                function formatDatetimeLocal(date) {
                                    const pad = (value) => String(value).padStart(2, '0');
                                    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
                                }

                                function calculateDuration() {
                                    if (!startInput.value || (!endInput.value && !timerInterval)) {
                                        displayDuration.value = '-';
                                        return;
                                    }

                                    const start = new Date(startInput.value);
                                    const now = endInput.value ? new Date(endInput.value) : new Date();
                                    
                                    let totalMinutes = 0;
                                    if (isPaused && pauseStart) {
                                        // Während Pause: Zeit bis Pause-Start
                                        totalMinutes = Math.max(0, Math.round((pauseStart - start) / 60000));
                                    } else {
                                        // Normal oder nach Pause-Ende: Zeit bis jetzt
                                        totalMinutes = Math.max(0, Math.round((now - start) / 60000));
                                    }
                                    
                                    const pausedMinutes = Number(pauseInput.value || 0);
                                    const nettoMinutes = Math.max(0, totalMinutes - pausedMinutes);

                                    displayDuration.value = `${totalMinutes} Min brutto · ${pausedMinutes} Min Pause · ${nettoMinutes} Min netto`;
                                }

                                function updatePauseDisplay() {
                                    displayPause.value = `${Number(pauseInput.value || 0)} Min`;
                                }

                                function startTimer() {
                                    if (startInput.value) {
                                        return;
                                    }

                                    const now = new Date();
                                    startInput.value = formatDatetimeLocal(now);
                                    displayStart.value = formatLocal(now);
                                    displayEnd.value = 'Noch nicht gestoppt';
                                    pauseInput.value = pauseInput.value || 0;
                                    updatePauseDisplay();
                                    calculateDuration();

                                    startBtn.disabled = true;
                                    pauseBtn.disabled = false;
                                    stopBtn.disabled = false;
                                    saveBtn.disabled = true;

                                    updateStatus('Läuft...', 'bi-circle-fill text-success', 'success');
                                    timerInterval = setInterval(calculateDuration, 1000);
                                    heartbeatInterval = setInterval(() => {
                                        fetch('/heartbeat', { 
                                            method: 'GET', 
                                            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
                                        });
                                    }, 600000); // Alle 10 Minuten Session verlängern
                                }

                                function togglePause() {
                                    if (!startInput.value || endInput.value) {
                                        return;
                                    }

                                    const now = new Date();
                                    if (!isPaused) {
                                        pauseStart = now;
                                        isPaused = true;
                                        pauseBtn.querySelector('span').textContent = 'Pause beenden';
                                        pauseBtn.classList.remove('btn-warning');
                                        pauseBtn.classList.add('btn-success');
                                        pauseBtn.querySelector('i').className = 'bi bi-play-circle-fill fs-4 me-2';
                                        updateStatus('Pausiert', 'bi-pause-circle-fill text-warning', 'warning');
                                    } else {
                                        const diff = Math.round((now - pauseStart) / 60000);
                                        pauseInput.value = String(Number(pauseInput.value || 0) + diff);
                                        isPaused = false;
                                        pauseStart = null;
                                        pauseBtn.querySelector('span').textContent = 'Pause';
                                        pauseBtn.classList.remove('btn-success');
                                        pauseBtn.classList.add('btn-warning');
                                        pauseBtn.querySelector('i').className = 'bi bi-pause-circle-fill fs-4 me-2';
                                        updatePauseDisplay();
                                        updateStatus('Läuft...', 'bi-circle-fill text-success', 'success');
                                    }

                                    calculateDuration();
                                }

                                function stopTimer() {
                                    if (!startInput.value || endInput.value) {
                                        return;
                                    }

                                    const now = new Date();
                                    if (isPaused && pauseStart) {
                                        const diff = Math.round((now - pauseStart) / 60000);
                                        pauseInput.value = String(Number(pauseInput.value || 0) + diff);
                                        isPaused = false;
                                        pauseStart = null;
                                        pauseBtn.querySelector('span').textContent = 'Pause';
                                        pauseBtn.classList.remove('btn-success');
                                        pauseBtn.classList.add('btn-warning');
                                        pauseBtn.querySelector('i').className = 'bi bi-pause-circle-fill fs-4 me-2';
                                        updatePauseDisplay();
                                    }

                                    endInput.value = formatDatetimeLocal(now);
                                    displayEnd.value = formatLocal(now);
                                    calculateDuration();

                                    startBtn.disabled = true;
                                    pauseBtn.disabled = true;
                                    stopBtn.disabled = true;
                                    saveBtn.disabled = false;

                                    updateStatus('Beendet', 'bi-check-circle-fill text-danger', 'danger');
                                    clearInterval(timerInterval);
                                    clearInterval(heartbeatInterval);
                                    heartbeatInterval = null;
                                }

                                startBtn.addEventListener('click', startTimer);
                                pauseBtn.addEventListener('click', togglePause);
                                stopBtn.addEventListener('click', stopTimer);

                                // Timer startet immer frisch - keine alten Werte verwenden
                                calculateDuration();
                                updatePauseDisplay();
                            });
                        </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</div>
@endsection