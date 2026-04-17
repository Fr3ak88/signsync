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
    <h5 class="fw-bold">4. Transparenz der Datenweitergabe (Subprozessoren)</h5>
    <p>Zur Erbringung unserer Dienstleistung setzen wir spezialisierte Partnerunternehmen ein, mit denen entsprechende Verträge zur Auftragsverarbeitung (Art. 28 DSGVO) bestehen:</p>
    <div class="table-responsive">
        <table class="table table-sm table-bordered bg-white">
            <thead class="bg-light text-muted">
                <tr>
                    <th>Dienstleister</th>
                    <th>Zweck</th>
                    <th>Sicherheitsgarantie</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>1blu AG</strong>, Berlin (DE)</td>
                    <td>Infrastruktur, Server-Hosting & Backups</td>
                    <td>AVV, Standort Deutschland, ISO-Konformität</td>
                </tr>
                <tr>
                    <td><strong>Mollie B.V.</strong>, Amsterdam (NL)</td>
                    <td>Zahlungsabwicklung</td>
                    <td>PCI-DSS zertifiziert, EU-Sitz</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p class="small text-muted"><i class="bi bi-info-circle me-1"></i> <strong>Keine Drittlandübermittlung:</strong> Wir nutzen keine Dienstleister in den USA (wie AWS, Google oder Microsoft) für die Kernprozesse der Applikation. Sämtliche personenbezogene Daten verbleiben innerhalb der EU/EWR.</p>
</section>

<section class="mb-4 border-start border-success border-4 ps-3">
    <h5 class="fw-bold text-success">5. Technische Details zur Datenverarbeitung & Sicherheit</h5>
    <div class="row">
        <div class="col-md-6">
            <h6 class="fw-bold">Datenverarbeitung im Detail:</h6>
            <ul class="small">
                <li><strong>Daten-Hashing:</strong> Alle generierten Monatsberichte werden mittels SHA-256 kryptografisch versiegelt.</li>
                <li><strong>Zugriffsschutz:</strong> Passwort-Hashing über Bcrypt (Work-Factor 10).</li>
                <li><strong>Mandanten-Isolation:</strong> Logische Trennung auf Datenbankebene (Row-Level-Security).</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold">Aufbewahrung & Löschung:</h6>
            <ul class="small">
                <li><strong>Server-Logs:</strong> Automatische Löschung nach 14 Tagen (rotierend).</li>
                <li><strong>Abonnement-Daten:</strong> Löschung nach Kündigung und Ablauf der steuerlichen Aufbewahrungsfrist (10 Jahre gemäß AO).</li>
                <li><strong>Benutzer-Content:</strong> Manuelle Löschung durch den Admin jederzeit möglich.</li>
            </ul>
        </div>
    </div>
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
            
            <section class="mb-4">
    <h5 class="fw-bold">10. Auftragsverarbeitung (AVV) nach Art. 28 DSGVO</h5>
    <div class="p-3 border rounded bg-white">
        <p>Für gewerbliche Nutzer (Träger der Schulbegleitung) agiert SignSync als Auftragsverarbeiter. Wir stellen hierfür einen rechtskonformen <strong>Vertrag zur Auftragsverarbeitung (AVV)</strong> gemäß Art. 28 DSGVO bereit.</p>
        <p class="mb-0">Kunden können diesen Vertrag direkt im Administrationsbereich unter „Einstellungen > Rechtliches“ einsehen und digital abschließen. Dieser Vertrag regelt detailliert die Pflichten zur Datensicherheit und die Weisungsgebundenheit der Verarbeitung.</p>
    </div>
</section>

<section class="mb-4">
    <h5 class="fw-bold">11. Technische und Organisatorische Maßnahmen (TOM)</h5>
    <p>Zum Schutz der hochsensiblen Sozialdaten setzen wir über den Standard hinausgehende Maßnahmen ein:</p>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 p-3 bg-light border-0 shadow-sm">
                <h6 class="fw-bold"><i class="bi bi-diagram-3 me-2"></i>Mandantentrennung</h6>
                <p class="small text-muted mb-0">Strikte logische Trennung der Datenbank-Ressourcen. Ein Zugriff von Organisation A auf Daten der Organisation B ist technisch auf Datenbankebene ausgeschlossen.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-3 bg-light border-0 shadow-sm">
                <h6 class="fw-bold"><i class="bi bi-eye-slash me-2"></i>Rollen- & Berechtigungskonzept</h6>
                <p class="small text-muted mb-0">Zugriff erfolgt nach dem „Need-to-know“-Prinzip. Mitarbeiter sehen nur die ihnen zugewiesenen Fälle; Administratoren haben ausschließlich Zugriff auf die Daten ihres eigenen Mandanten.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-3 bg-light border-0 shadow-sm">
                <h6 class="fw-bold"><i class="bi bi-database-lock me-2"></i>Backup & Recovery</h6>
                <p class="small text-muted mb-0">Tägliche, verschlüsselte Backups auf geografisch getrennten Systemen innerhalb Deutschlands (1blu Infrastruktur), um Datenverlust vorzubeugen.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-3 bg-light border-0 shadow-sm">
                <h6 class="fw-bold"><i class="bi bi-journal-text me-2"></i>Audit-Logging</h6>
                <p class="small text-muted mb-0">Kritische Systemaktionen (Login-Versuche, Generierung von Leistungsnachweisen) werden protokolliert, um Missbrauch proaktiv zu erkennen.</p>
            </div>
        </div>
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
            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Zurück
                </a>
            </div>
        </div>
    </div>
</div>
@endsection