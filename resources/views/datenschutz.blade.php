@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-lock me-2"></i>Datenschutzerklärung</h1>

            <section class="mb-4">
                <h5 class="fw-bold">1. Datenschutz auf einen Blick</h5>
                <p>Diese Datenschutzerklärung klärt Sie über die Art, den Umfang und Zweck der Verarbeitung von personenbezogenen Daten innerhalb unserer Applikation <strong>SignSync</strong> auf.</p>
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

            <section class="mb-4">
                <h5 class="fw-bold">3. Datenerfassung in SignSync</h5>
                <ul>
                    <li><strong>Benutzerdaten Firma:</strong> Firma, Name, Adresse und E-Mail des Unternehmens zur Account-Verwaltung.</li>
                    <li><strong>Benutzerdaten Mitarbeiter:</strong> Name und E-Mail zur Account-Verwaltung.</li>
                    <li><strong>Einsatzdaten:</strong> Namen der betreuten Schüler, Zeiten und Tätigkeitsnachweise.</li>
                    <li><strong>Digitale Signatur:</strong> Grafische Unterschriften der Schulverantwortlichen zur Verifizierung der Einsätze.</li>
                    <li><strong>Technische Daten:</strong> IP-Adresse, Browsertyp und Betriebssystem (Server-Logfiles) zur Sicherstellung des Betriebs und zur Missbrauchserkennung.</li>
                </ul>
                <p>Diese Daten dienen ausschließlich der Erstellung von Leistungsnachweisen und der Abrechnung der geleisteten Stunden.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Zahlungsabwicklung über Mollie</h5>
                    <p>
                        Wir bieten auf unserer Webseite die Bezahlung via den Zahlungsdienstleister <strong>Mollie</strong> an. 
                        Anbieter ist die <em>Mollie B.V., Keizersgracht 126, 1015 CW Amsterdam, Niederlande</em> (nachfolgend „Mollie“).
                    </p>
                    <ul>
                    <li><strong>Art der Daten:</strong> Wenn Sie sich für ein kostenpflichtiges Abonnement entscheiden, werden die von Ihnen angegebenen Zahlungsdaten (z. B. Kreditkartendaten, Bankverbindung, gewählte Zahlungsmethode) sowie Rechnungsdaten (Name, E-Mail-Adresse) an Mollie übermittelt.</li>
                    <li><strong>Zweck der Verarbeitung:</strong> Die Übermittlung erfolgt ausschließlich zum Zwecke der Zahlungsabwicklung und zur Durchführung regelmäßiger Lastschriften bzw. Abbuchungen im Rahmen Ihres Abonnements.</li>
                    <li><strong>Rechtsgrundlage:</strong> Die Weitergabe Ihrer Daten an Mollie erfolgt auf Grundlage von <strong>Art. 6 Abs. 1 lit. b DSGVO</strong> (Vertragserfüllung).</li>
                    <li><strong>Datensicherheit:</strong> Mollie verarbeitet Ihre Daten innerhalb der Europäischen Union und hält höchste Sicherheitsstandards für Zahlungsdaten (PCI-DSS-Konformität) ein.</li>
                    </ul>

                    <p class="mt-3 small text-muted">
                        Details hierzu finden Sie in der Datenschutzerklärung von Mollie unter: 
                        <a href="https://www.mollie.com/de/privacy" target="_blank" class="text-decoration-none">https://www.mollie.com/de/privacy</a>
                    </p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">5. Rechtsgrundlagen</h5>
                <p>Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO (Vertragserfüllung) sowie Art. 9 Abs. 2 lit. h DSGVO, sofern Gesundheitsdaten im Rahmen der Schulbegleitung berührt werden.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. SSL- bzw. TLS-Verschlüsselung</h5>
                <p>Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, wie zum Beispiel Zeiterfassungen oder Unterschriften, die Sie an uns als Seitenbetreiber senden, eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://“ auf „https://“ wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.</p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">7. Verzicht auf Analyse-Tools und Social Media</h5>
                <p>Datenschutz hat bei uns höchste Priorität. Wir verwenden auf dieser Webseite <strong>keine Analyse-Tools</strong> (wie z. B. Google Analytics) und <strong>keine Social-Media-Plugins</strong> oder Tracking-Pixel.</p>
                <p>Ihr Besuch auf SignSync wird nicht durch Drittanbieter ausgewertet. Es findet kein Datentransfer an Werbenetzwerke oder soziale Netzwerke statt. Wir erfassen lediglich technisch notwendige Daten, die für den stabilen Betrieb und die rechtssichere Zeiterfassung zwingend erforderlich sind.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">8. Ihre Rechte</h5>
                <p>Sie haben jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Daten verarbeitung sowie ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten.</p>
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