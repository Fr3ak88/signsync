@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-11 card border-0 shadow-sm p-5">

            <h1 class="fw-bold text-success mb-2">
                <i class="bi bi-shield-check me-2"></i>Datenschutzerklärung
            </h1>
            <p class="text-muted mb-4 small">
                DSGVO-konform (Audit-optimiert) | Version 2.2 (April 2026)
            </p>

            {{-- 1 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    1. Verantwortliche Stelle & Rollen
                </h5>

                <p>
                    <strong>Verantwortlicher im Sinne der DSGVO:</strong><br>
                    Fritzler-Solution, Eugen Fritzler<br>
                    Ahlener Str. 107, 59073 Hamm<br>
                    E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a>
                </p>

                <div class="alert alert-light border small">
                    <strong>Hinweis zur Rollenverteilung:</strong><br>
                    Für den Betrieb dieser Website agieren wir als <strong>Verantwortlicher (Art. 4 Nr. 7 DSGVO)</strong>.
                    Für die Verarbeitung von Kundendaten im Rahmen der Plattformleistungen können wir – abhängig vom Nutzungskontext – als
                    <strong>Auftragsverarbeiter (Art. 28 DSGVO)</strong> tätig werden. In diesem Fall erfolgt die Verarbeitung ausschließlich
                    auf Weisung des jeweiligen Kunden.
                </div>
            </section>

            {{-- 2 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    2. Datenerfassung auf der Website (Server-Logs)
                </h5>

                <p class="small">
                    Beim Aufruf der Website werden technisch notwendige Daten verarbeitet:
                </p>

                <ul class="small">
                    <li>IP-Adresse (gekürzt, soweit technisch möglich)</li>
                    <li>Datum und Uhrzeit</li>
                    <li>Browsertyp und Version</li>
                    <li>Betriebssystem</li>
                    <li>aufgerufene Seiten</li>
                </ul>

                <p class="small">
                    <strong>Zweck:</strong> Sicherheit, Stabilität, Fehleranalyse<br>
                    <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. f DSGVO<br>
                    <strong>Speicherdauer:</strong> 14 Tage bis maximal 90 Tage (Sicherheitsbedarf)
                </p>
            </section>

            {{-- 3 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    3. Cookies & lokale Speicherung
                </h5>

                <p class="small">
                    Es werden ausschließlich technisch notwendige Cookies eingesetzt.
                    Eine Verarbeitung zu Tracking- oder Marketingzwecken findet nicht statt.
                </p>

                <p class="small">
                    <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. f DSGVO
                </p>
            </section>

            {{-- 4 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    4. Kontaktaufnahme
                </h5>

                <p class="small">
                    Bei Kontaktaufnahme werden folgende Daten verarbeitet:
                </p>

                <ul class="small">
                    <li>Name (falls angegeben)</li>
                    <li>E-Mail-Adresse</li>
                    <li>Inhalt der Anfrage</li>
                </ul>

                <p class="small">
                    <strong>Zweck:</strong> Bearbeitung von Anfragen<br>
                    <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. b DSGVO<br>
                    <strong>Speicherdauer:</strong> bis Abschluss der Anfrage
                </p>
            </section>

            {{-- 5 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    5. Auftragsverarbeiter
                </h5>

                <p class="small">
                    Wir setzen folgende Kategorien von Dienstleistern ein:
                </p>

                <ul class="small">
                    <li>Hosting-Infrastruktur (EU-basiert)</li>
                    <li>IT-Wartung und Sicherheit</li>
                    <li>E-Mail-Dienste</li>
                </ul>

                <p class="small">
                    Mit allen Dienstleistern bestehen Verträge zur Auftragsverarbeitung gemäß Art. 28 DSGVO.
                </p>
            </section>

            {{-- 6 --}}
           <section class="mb-5">
    <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
        6. Drittlandübermittlung
    </h5>

    <p class="small">
        Eine Verarbeitung oder Speicherung personenbezogener Daten außerhalb der Europäischen Union (EU) oder des Europäischen Wirtschaftsraums (EWR) findet nicht statt.
        Alle Daten werden ausschließlich auf Servern innerhalb der EU / des EWR verarbeitet.
    </p>

    <p class="small">
        Es werden keine Drittanbieter eingesetzt, die personenbezogene Daten in Drittländer übermitteln.
    </p>

    <p class="small">
        Sollte sich dies in Zukunft ändern, erfolgt eine Anpassung dieser Datenschutzerklärung
        sowie eine Verarbeitung ausschließlich im Einklang mit den Art. 44 ff. DSGVO und den dort vorgesehenen Garantien.
    </p>
</section>

            {{-- 7 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    7. Technische & organisatorische Maßnahmen (TOM)
                </h5>

                <ul class="small">
                    <li>Verschlüsselung der Datenübertragung (TLS)</li>
                    <li>Zugriffsbeschränkungen (rollenbasiert)</li>
                    <li>Regelmäßige Sicherheitsupdates</li>
                    <li>Backup-Mechanismen mit Wiederherstellbarkeit</li>
                </ul>
            </section>

            {{-- 8 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    8. Speicherdauer
                </h5>

                <ul class="small">
                    <li>Server-Logs: bis zu 90 Tage</li>
                    <li>Kontaktanfragen: bis zur abschließenden Bearbeitung</li>
                    <li>Vertragsdaten: gesetzliche Aufbewahrungsfristen</li>
                </ul>
            </section>

            {{-- 9 --}}
            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">
                    9. Rechte der betroffenen Personen
                </h5>

                <p class="small">
                    Sie haben jederzeit Rechte auf:
                    Auskunft, Berichtigung, Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch.
                </p>

                <p class="small">
                    Kontakt: info@signsync.de
                </p>
            </section>

            <section class="mb-5"> <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">6. Ihre Rechte & Kontakt</h5> <p class="small">Sie haben Rechte auf Auskunft, Löschung, Berichtigung und Datenübertragbarkeit. Bitte senden Sie Anfragen an <strong>info@signsync.de</strong>.</p> <div class="p-3 bg-light border border-success rounded text-center mt-4"> <p class="mb-2 small fw-bold text-success">Interessiert an weiteren technischen Details?</p> <a href="{{ route('transparenz') }}" class="btn btn-success btn-sm px-4 shadow-sm">Zum technischen Transparenzbericht</a> </div> </section>

            {{-- Footer --}}
            <div class="mt-4 text-center border-top pt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                    Zurück
                </a>
            </div>

        </div>
    </div>
</div>
@endsection