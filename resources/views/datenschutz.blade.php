@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-lock me-2"></i>Datenschutzerklärung</h1>

            <section class="mb-4">
                <h5 class="fw-bold">1. Datenschutz auf einen Blick</h5>
                <p>Diese Datenschutzerklärung klärt Sie über die Art, den Umfang und Zweck der Verarbeitung von personenbezogenen Daten innerhalb unserer SaaS-Applikation <strong>SignSync</strong> auf. Wir nehmen den Schutz Ihrer Daten und der Daten der betreuten Schüler sehr ernst und verzichten bewusst auf jegliches Tracking.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">2. Verantwortliche Stelle</h5>
                <p>
                    Fritzler-Solution<br>
                    Eugen Fritzler<br>
                    Ahlener Str. 107<br>
                    59073 Hamm<br>
                    E-Mail: info@signsync.de
                </p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">3. Hosting in Deutschland (1blu AG)</h5>
                <p>Unsere Applikation wird bei der <strong>1blu AG, Stromstraße 1-5, 10551 Berlin</strong> gehostet.</p>
                <ul>
                    <li><strong>Serverstandort:</strong> Ausschließlich Deutschland.</li>
                    <li><strong>Sicherheit:</strong> Die Datenverarbeitung erfolgt auf Basis eines Vertrages über Auftragsverarbeitung (AVV) gemäß Art. 28 DSGVO. Die Server der 1blu AG werden in hochsicheren Rechenzentren betrieben.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Datenerfassung in SignSync</h5>
                <p>Wir verarbeiten folgende Datenkategorien zur Bereitstellung unserer Dienstleistung:</p>
                <ul>
                    <li><strong>Benutzerdaten Firma:</strong> Firma, Name, Adresse und E-Mail des Unternehmens zur Account-Verwaltung.</li>
                    <li><strong>Benutzerdaten Mitarbeiter:</strong> Name und E-Mail zur Account-Verwaltung und Authentifizierung.</li>
                    <li><strong>Einsatzdaten:</strong> Namen der betreuten Schüler, Zeiten und Tätigkeitsnachweise.</li>
                    <li><strong>Sensible Daten (Art. 9 DSGVO):</strong> Da im Rahmen der Schulbegleitung indirekt Gesundheitsdaten oder Förderbedarfe dokumentiert werden, unterliegen diese einer besonderen Schutzstufe (siehe Punkt 6).</li>
                    <li><strong>Digitale Signatur:</strong> Grafische Unterschriften zur Verifizierung der Einsätze.</li>
                    <li><strong>Technische Daten:</strong> IP-Adresse, Browsertyp und Betriebssystem (Server-Logfiles) zur Sicherstellung des Betriebs und zur Missbrauchserkennung.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">5. Technische Sicherheit und Versiegelung</h5>
                <ul>
                    <li><strong>SSL-/TLS-Verschlüsselung:</strong> SignSync nutzt eine durchgehende Verschlüsselung zum Schutz vertraulicher Inhalte während der Übertragung.</li>
                    <li><strong>Digitale Versiegelung:</strong> Jeder abgeschlossene Beleg wird mittels eines <strong>SHA-256 Hash-Verfahrens</strong> mit einer eindeutigen Sicherheits-ID versehen. Dies stellt die Integrität sicher; nachträgliche Manipulationen werden dadurch sofort erkennbar.</li>
                    <li><strong>Geschützte Ablage:</strong> Generierte PDF-Belege werden in einem nicht-öffentlichen Bereich des Servers gespeichert (Private Storage) und sind nur für authentifizierte Nutzer zugänglich.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. Rechtsgrundlagen</h5>
                <p>Die Verarbeitung erfolgt auf Grundlage von:</p>
                <ul>
                    <li><strong>Art. 6 Abs. 1 lit. b DSGVO</strong> (Vertragserfüllung).</li>
                    <li><strong>Art. 9 Abs. 2 lit. h DSGVO</strong> i.V.m. § 22 Abs. 1 Nr. 1 lit. b BDSG, sofern Gesundheitsdaten im Rahmen der Schulbegleitung zur Dokumentation gegenüber Leistungsträgern berührt werden.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">7. Zahlungsabwicklung über Mollie</h5>
                <p>
                    Wir bieten die Bezahlung via <strong>Mollie</strong> an. 
                    Anbieter ist die <em>Mollie B.V., Keizersgracht 126, 1015 CW Amsterdam, Niederlande</em>.
                </p>
                <ul>
                    <li><strong>Art der Daten:</strong> Zahlungsdaten (z. B. Bankverbindung, Kreditkarte) sowie Rechnungsdaten (Name, E-Mail).</li>
                    <li><strong>Zweck:</strong> Abwicklung der Abonnement-Zahlungen auf Grundlage von <strong>Art. 6 Abs. 1 lit. b DSGVO</strong>.</li>
                </ul>
                <p class="mt-2 small text-muted">
                    Details unter: <a href="https://www.mollie.com/de/privacy" target="_blank" class="text-decoration-none">https://www.mollie.com/de/privacy</a>
                </p>
            </section>

            <section class="mb-4 bg-light p-3 border-start border-4 border-success">
                <h5 class="fw-bold">8. Verzicht auf Analyse-Tools und Social Media</h5>
                <p>Wir verwenden <strong>keine Analyse-Tools</strong> (wie Google Analytics) und <strong>keine Social-Media-Plugins</strong> oder Tracking-Pixel. Ihr Besuch auf SignSync wird nicht durch Drittanbieter ausgewertet.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">9. Ihre Rechte</h5>
                <p>Sie haben jederzeit das Recht auf unentgeltliche Auskunft (Art. 15 DSGVO), Berichtigung (Art. 16 DSGVO), Löschung (Art. 17 DSGVO) oder Einschränkung der Verarbeitung Ihrer Daten sowie das Recht auf Datenübertragbarkeit (Art. 20 DSGVO).</p>
            </section>
            
            <section class="mb-4 text-center">
                <div class="p-3 bg-light rounded border">
                    <p class="mb-0 text-muted italic">
                        <i class="bi bi-info-circle me-2"></i>
                        „Wir verwenden ausschließlich technisch notwendige Session-Cookies, um die Funktionalität des Logins zu gewährleisten.“
                    </p>
                </div>
            </section>

            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Zurück
                </a>
            </div>
        </div>
    </div>
</div>
@endsection