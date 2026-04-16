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
                        <input type="hidden" name="start_zeit" id="start_zeit" value="{{ old('start_zeit') }}">
                        <input type="hidden" name="ende_zeit" id="ende_zeit" value="{{ old('ende_zeit') }}">
                        <input type="hidden" name="pause_minuten" id="pause_minuten" value="{{ old('pause_minuten', 0) }}">

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
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                <button type="button" id="timer-start-btn" class="btn btn-success py-2 fw-bold text-white shadow-sm">
                                    <i class="bi bi-play-fill me-1"></i> Start
                                </button>
                                <button type="button" id="timer-pause-btn" class="btn btn-warning py-2 fw-bold text-white shadow-sm" disabled>
                                    <i class="bi bi-pause-fill me-1"></i> Pause
                                </button>
                                <button type="button" id="timer-stop-btn" class="btn btn-danger py-2 fw-bold text-white shadow-sm" disabled>
                                    <i class="bi bi-stop-fill me-1"></i> Stop
                                </button>
                            </div>

                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-uppercase text-muted">Gestartet</label>
                                    <input type="text" id="display-start" class="form-control bg-white" value="{{ old('start_zeit') ? old('start_zeit') : 'Noch nicht gestartet' }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-uppercase text-muted">Beendet</label>
                                    <input type="text" id="display-end" class="form-control bg-white" value="{{ old('ende_zeit') ? old('ende_zeit') : 'Noch nicht gestoppt' }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-uppercase text-muted">Pause</label>
                                    <input type="text" id="display-pause" class="form-control bg-white" value="{{ old('pause_minuten', 0) }} Min" readonly>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label small text-uppercase text-muted">Laufzeit</label>
                                <input type="text" id="display-duration" class="form-control bg-white" value="-" readonly>
                            </div>

                            @error('start_zeit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('ende_zeit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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

                                let pauseStart = null;
                                let isPaused = false;
                                let timerInterval = null;

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
                                    return date.toISOString().slice(0, 16);
                                }

                                function calculateDuration() {
                                    if (!startInput.value) {
                                        displayDuration.value = '-';
                                        return;
                                    }

                                    const start = new Date(startInput.value);
                                    const end = endInput.value ? new Date(endInput.value) : new Date();
                                    const totalMinutes = Math.max(0, Math.round((end - start) / 60000));
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

                                    timerInterval = setInterval(calculateDuration, 1000);
                                }

                                function togglePause() {
                                    if (!startInput.value || endInput.value) {
                                        return;
                                    }

                                    const now = new Date();
                                    if (!isPaused) {
                                        pauseStart = now;
                                        isPaused = true;
                                        pauseBtn.textContent = 'Pause beenden';
                                        pauseBtn.classList.remove('btn-warning');
                                        pauseBtn.classList.add('btn-success');
                                        pauseBtn.querySelector('i').className = 'bi bi-play-fill me-1';
                                    } else {
                                        const diff = Math.round((now - pauseStart) / 60000);
                                        pauseInput.value = String(Number(pauseInput.value || 0) + diff);
                                        isPaused = false;
                                        pauseStart = null;
                                        pauseBtn.textContent = 'Pause';
                                        pauseBtn.classList.remove('btn-success');
                                        pauseBtn.classList.add('btn-warning');
                                        pauseBtn.querySelector('i').className = 'bi bi-pause-fill me-1';
                                        updatePauseDisplay();
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
                                        pauseBtn.textContent = 'Pause';
                                        pauseBtn.classList.remove('btn-success');
                                        pauseBtn.classList.add('btn-warning');
                                        pauseBtn.querySelector('i').className = 'bi bi-pause-fill me-1';
                                        updatePauseDisplay();
                                    }

                                    endInput.value = formatDatetimeLocal(now);
                                    displayEnd.value = formatLocal(now);
                                    calculateDuration();

                                    startBtn.disabled = true;
                                    pauseBtn.disabled = true;
                                    stopBtn.disabled = true;
                                    saveBtn.disabled = false;

                                    clearInterval(timerInterval);
                                }

                                startBtn.addEventListener('click', startTimer);
                                pauseBtn.addEventListener('click', togglePause);
                                stopBtn.addEventListener('click', stopTimer);

                                if (startInput.value) {
                                    startBtn.disabled = true;
                                    if (displayStart.value === startInput.value) {
                                        displayStart.value = formatLocal(startInput.value);
                                    }
                                }
                                if (endInput.value) {
                                    pauseBtn.disabled = true;
                                    stopBtn.disabled = true;
                                    saveBtn.disabled = false;
                                    if (displayEnd.value === endInput.value) {
                                        displayEnd.value = formatLocal(endInput.value);
                                    }
                                } else if (startInput.value) {
                                    pauseBtn.disabled = false;
                                    stopBtn.disabled = false;
                                    saveBtn.disabled = true;
                                    timerInterval = setInterval(calculateDuration, 1000);
                                }

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