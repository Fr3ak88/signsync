@extends('layouts.app')

@push('styles')
<style>
    .transition-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .transition-hover:hover { transform: translateY(-10px); box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important; }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.15); }
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.15); }
    .bg-dark-soft { background-color: rgba(33, 37, 41, 0.15); }
    .border-active { border: 3px solid #0d6efd !important; position: relative; }
    .active-ribbon { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); z-index: 10; padding: 5px 15px; border-radius: 20px; }
</style>
@endpush

@section('content')
@if(session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger shadow-sm border-0">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    </div>
@endif

@if(session('info'))
    <div class="container mt-4">
        <div class="alert alert-info shadow-sm border-0">
            <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
        </div>
    </div>
@endif

<section class="py-5 bg-light">
    <div class="container">
        {{-- Header --}}
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Einfache Preise für große Aufgaben</h1>
            <p class="lead text-muted">Wählen Sie das passende Modell für Ihre Einrichtung.</p>
            
            @auth
                @if(auth()->user()->plan_name)
                    <div class="alert alert-info d-inline-block px-4 shadow-sm border-0">
                        Sie nutzen aktuell das <strong>{{ ucfirst(auth()->user()->plan_name) }}-Paket</strong>.
                    </div>
                @endif
            @endauth
        </div>

        {{-- Preistabellen --}}
        <div class="row g-4 justify-content-center">
            
            {{-- STARTER --}}
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm transition-hover {{ (auth()->check() && auth()->user()->plan_name === 'starter') ? 'border-active' : '' }}">
                    @if(auth()->check() && auth()->user()->plan_name === 'starter')
                        <span class="badge bg-primary active-ribbon shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Aktiv</span>
                    @endif
                    <div class="card-body p-5">
                        <div class="mb-4 text-start">
                            <span class="badge bg-secondary-soft text-secondary mb-2 px-3 py-2 text-start">Starter</span>
                            <h2 class="h1 fw-bold">19 €*<span class="fs-5 text-muted">/Monat</span></h2>
                        </div>
                        <ul class="list-unstyled mb-5 text-start">
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> <strong>Bis 5</strong> Begleiter</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> Digitale eSignatur</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> PDF-Export & Archiv</li>
                        </ul>
                        
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-3 fw-bold">
                                Jetzt registrieren
                            </a>
                        @else
                            <form action="{{ route('plans.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="starter">
                                <button type="submit" class="btn btn-outline-primary w-100 py-3 fw-bold {{ auth()->user()->plan_name === 'starter' ? 'disabled' : '' }}">
                                    {{ auth()->user()->plan_name === 'starter' ? 'Ihr aktueller Plan' : 'Jetzt starten' }}
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>

            {{-- TEAM --}}
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-lg transition-hover {{ (auth()->check() && auth()->user()->plan_name === 'team') ? 'border-active' : 'border-primary' }}">
                    @if(auth()->check() && auth()->user()->plan_name === 'team')
                        <span class="badge bg-primary active-ribbon shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Aktiv</span>
                    @else
                        <div class="card-header bg-primary text-white text-center fw-bold py-2 border-0">Empfehlung</div>
                    @endif
                    <div class="card-body p-5 text-start">
                        <div class="mb-4 text-primary text-start">
                            <span class="badge bg-primary-soft text-primary mb-2 px-3 py-2">Team</span>
                            <h2 class="h1 fw-bold text-dark">49 €*<span class="fs-5 text-muted">/Monat</span></h2>
                        </div>
                        <ul class="list-unstyled mb-5 text-start">
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> <strong>Bis 20</strong> Begleiter</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> Digitale eSignatur</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> PDF-Export & Archiv</li>
                        </ul>
                        
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary text-white shadow w-100 py-3 fw-bold">
                                Jetzt registrieren
                            </a>
                        @else
                            <form action="{{ route('plans.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="team">
                                <button type="submit" class="btn btn-primary text-white shadow w-100 py-3 fw-bold {{ auth()->user()->plan_name === 'team' ? 'disabled' : '' }}">
                                    {{ auth()->user()->plan_name === 'team' ? 'Ihr aktueller Plan' : 'Plan wählen' }}
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>

            {{-- PROFESSIONAL --}}
            <div class="col-lg-4 col-md-6 text-start">
                <div class="card h-100 border-0 shadow-sm transition-hover {{ (auth()->check() && auth()->user()->plan_name === 'pro') ? 'border-active' : '' }}">
                    @if(auth()->check() && auth()->user()->plan_name === 'pro')
                        <span class="badge bg-primary active-ribbon shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Aktiv</span>
                    @endif
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <span class="badge bg-dark-soft text-dark mb-2 px-3 py-2">Professional</span>
                            <h2 class="h1 fw-bold text-dark text-start">99 €*<span class="fs-5 text-muted">/Monat</span></h2>
                        </div>
                        <ul class="list-unstyled mb-5 text-start">
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> <strong>Bis 50</strong> Begleiter</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> Digitale eSignatur</li>
                            <li class="mb-3"><i class="bi bi-check2 text-primary me-2"></i> PDF-Export & Archiv</li>
                            <li class="mb-3 small text-muted"><i class="bi bi-plus-circle text-primary me-2"></i> 3 € je weiterer Benutzer</li>
                        </ul>
                        
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-dark w-100 py-3 fw-bold">
                                Jetzt registrieren
                            </a>
                        @else
                            <form action="{{ route('plans.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="pro">
                                <button type="submit" class="btn btn-outline-dark w-100 py-3 fw-bold {{ auth()->user()->plan_name === 'pro' ? 'disabled' : '' }}">
                                    {{ auth()->user()->plan_name === 'pro' ? 'Ihr aktueller Plan' : 'Pro wählen' }}
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>

        </div> {{-- Ende Row --}}
        
        <div class="text-center mt-5">
            <p class="text-muted small">* Alle Preise zzgl. gesetzlicher MwSt. Monatlich kündbar. Die Abwicklung erfolgt sicher über Mollie.</p>
        </div>

        {{-- FAQ Bereich --}}
        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-lg-8 text-start">
                <h3 class="fw-bold text-center mb-4 text-dark text-center">Häufige Fragen zum Abo</h3>
                <div class="accordion shadow-sm text-start" id="accordionFAQ">
                    
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Kann ich mein Paket jederzeit wechseln?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Ja! Ein Upgrade auf ein größeres Paket wird nach erfolgreicher Zahlung sofort wirksam. Ein Downgrade ist ebenfalls möglich, sofern Ihre aktuelle Mitarbeiteranzahl in das kleinere Paket passt.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header text-start">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Gibt es eine Mindestlaufzeit?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                Nein. Alle SignSync-Pakete sind monatlich kündbar. Die Zahlung erfolgt sicher und bequem über Mollie (PayPal, Lastschrift, Kreditkarte).
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 text-start">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-start" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Account wird nicht sofort freigeschaltet?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body text-muted">
                                In der Regel erfolgt die Freischaltung sofort nach erfolgreicher Bestätigung durch Mollie. Bei Zahlung per SEPA-Lastschrift kann die Bearbeitungszeit durch die Banken jedoch 2-3 Werktage in Anspruch nehmen.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection