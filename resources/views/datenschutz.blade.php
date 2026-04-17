@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-check me-2"></i>Datenschutzerklärung & Transparenzbericht</h1>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">1. Allgemeine Hinweise</h5>
                <p>Diese Datenschutzerklärung informiert über Art, Umfang und Zweck der Verarbeitung personenbezogener Daten im Rahmen der Nutzung der Applikation <strong>SignSync</strong>. Wir behandeln Daten vertraulich und entsprechend der gesetzlichen Vorschriften (DSGVO, BDSG).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">2. Verantwortliche Stelle</h5>
                <p>
                    <strong>Fritzler-Solution</strong><br>
                    Eugen Fritzler, Ahlener Str. 107, 59073 Hamm<br>
                    E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a>
                </p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">3. Datenerfassung & Server-Logfiles</h5>
                <p>Beim Aufruf der Website werden automatisch folgende Daten erfasst:</p>
                <ul class="small text-muted">
                    <li>IP-Adresse (anonymisiert soweit technisch möglich)</li>
                    <li>Datum und Uhrzeit des Zugriffs</li>
                    <li>Browsertyp, Version und Betriebssystem</li>
                    <li>Aufgerufene Seiten / Ressourcen</li>
                    <li>Referrer-URL (falls übermittelt)</li>
                </ul>
                <p class="small"><strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. f DSGVO (Berechtigtes Interesse an Systemsicherheit und Fehleranalyse).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">4. Cookies und vergleichbare Technologien</h5>
                <p>SignSync verwendet <strong>ausschließlich technisch notwendige Cookies</strong> zur Sitzungsverwaltung (Session-ID). Rechtsgrundlage: Art. 6 Abs. 1 lit. f DSGVO. Es erfolgt kein Tracking durch Statistik- oder Marketing-Cookies.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">5. Kontaktaufnahme</h5>
                <p>Bei Kontaktaufnahme (E-Mail oder Support) werden Name, E-Mail-Adresse und Inhalt gespeichert. Zweck ist die Bearbeitung der Anfrage auf Grundlage von Art. 6 Abs. 1 lit. b (vorvertraglich) oder lit. f DSGVO.</p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success text-uppercase small">6. Zweck der Datenverarbeitung</h5>
                <p class="small mb-0">Die Verarbeitung erfolgt zur Bereitstellung der SaaS-Dienste, zur technischen Sicherheit, Kommunikation sowie zur Erstellung von Leistungsnachweisen in der Schulbegleitung. Eine Weiterverarbeitung erfolgt nur, wenn sie mit dem ursprünglichen Zweck vereinbar ist (Art. 5 Abs. 1 lit. b DSGVO).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">7. Weitergabe von Daten</h5>
                <p>Kategorien von Empfängern sind: <strong>Hosting-Anbieter (1blu AG)</strong>, <strong>Zahlungsdienstleister (Mollie B.V.)</strong> und ggf. IT-Support-Systeme. Eine Weitergabe erfolgt nur zur Vertragserfüllung oder bei gesetzlicher Pflicht.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">8. Auftragsverarbeitung (Art. 28 DSGVO)</h5>
                <p>Mit allen Kern-Dienstleistern bestehen Verträge zur Auftragsverarbeitung. Diese verarbeiten Daten ausschließlich nach Weisung. Betroffene Kategorien: Webhosting, Server-Infrastruktur, Zahlungsabwicklung und Sicherheits-Tools.</p>
            </section>

            <section class="mb-4 bg-light p-3 border rounded shadow-sm">
                <h5 class="fw-bold text-uppercase small text-muted">9. Drittlandübermittlung</h5>
                <p class="small">SignSync hostet primär in Deutschland. Eine Übermittlung in Drittländer (außerhalb EU/EWR) erfolgt nur, wenn ein Angemessenheitsbeschluss der EU-Kommission vorliegt (z. B. <strong>EU-US Data Privacy Framework</strong>) oder <strong>Standardvertragsklauseln (SCC)</strong> vorliegen. Derzeit werden die Kern-Datenprozesse innerhalb des EWR ausgeführt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">10. Speicherdauer</h5>
                <ul class="small">
                    <li><strong>Server-Logs:</strong> 14 Tage bis 14 Monate (je nach Sicherheitsbedarf).</li>
                    <li><strong>Signaturdaten & Belege:</strong> Entsprechend gesetzlicher Aufbewahrungspflichten (§ 147 AO, 10 Jahre).</li>
                    <li><strong>Kontaktanfragen:</strong> Bis zur abschließenden Bearbeitung.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold text-uppercase small text-muted">11. Ihre Rechte</h5>
                <p class="small">Sie haben Rechte auf Auskunft (Art. 15), Berichtigung (Art. 16), Löschung (Art. 17), Einschränkung (Art. 18), Datenübertragbarkeit (Art. 20) sowie Widerspruch (Art. 21). Zudem besteht ein Beschwerderecht bei einer Aufsichtsbehörde.</p>
            </section>

            <section class="mb-4">
                <div class="p-4 bg-light rounded border border-success text-center shadow-sm">
                    <h5 class="fw-bold text-success mb-2">
                        <i class="bi bi-file-earmark-medical me-2"></i>Tiefergehende technische Informationen
                    </h5>
                    <p class="small text-muted mb-3">
                        Detaillierte Angaben zur IT-Infrastruktur, Verschlüsselungsverfahren und der 
                        revisionssicheren Datenverarbeitung finden Sie in unserem separaten Bericht.
                    </p>
                    <a href="{{ route('transparenz') }}" class="btn btn-success btn-sm px-4">
                        Zum technischen Transparenzbericht
                    </a>
                </div>
            </section>
            
            <section class="mb-4 text-center">
                <div class="p-3 bg-light rounded border">
                    <p class="mb-0 text-muted italic">
                        <i class="bi bi-info-circle me-2"></i>
                        „Wir verwenden ausschließlich technisch notwendige Session-Cookies, um die Funktionalität des Logins zu gewährleisten.“
                    </p>
                </div>
            </section>
            <div class="mt-4 text-center border-top pt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Zurück
                </a>
            </div>
        </div>
    </div>
</div>
@endsection