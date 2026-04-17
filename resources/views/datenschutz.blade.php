@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-check me-2"></i>Datenschutzerklärung</h1>

            <section class="mb-4">
                <h5 class="fw-bold">1. Datenschutz auf einen Blick</h5>
                <p>Diese Datenschutzerklärung informiert Sie über die Verarbeitung personenbezogener Daten in der Applikation <strong>SignSync</strong>. Wir verzichten vollständig auf Tracking-Tools und setzen auf maximale Datensparsamkeit.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">2. Verantwortliche Stelle</h5>
                <p>Fritzler-Solution, Eugen Fritzler, Ahlener Str. 107, 59073 Hamm<br>
                E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a></p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">3. Hosting & Infrastruktur (Deutschland)</h5>
                <p>Unsere Applikation wird bei der <strong>1blu AG, Stromstraße 1-5, 10551 Berlin</strong> gehostet. Der Serverstandort ist ausschließlich Deutschland. Es besteht ein Vertrag zur Auftragsverarbeitung (AVV).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Art und Zweck der Datenerfassung</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white">
                        <thead class="table-light">
                            <tr>
                                <th>Datenkategorie</th>
                                <th>Konkrete Daten</th>
                                <th>Zweck</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Unternehmen</td>
                                <td>Firma, Anschrift, E-Mail</td>
                                <td>Account-Verwaltung</td>
                            </tr>
                            <tr>
                                <td>Mitarbeiter</td>
                                <td>Name, E-Mail</td>
                                <td>Login & Zuordnung</td>
                            </tr>
                            <tr>
                                <td>Einsatzdaten</td>
                                <td>Schülername, Zeiten</td>
                                <td>Leistungsnachweis</td>
                            </tr>
                            <tr>
                                <td>Signaturen</td>
                                <td>Grafische Unterschrift</td>
                                <td>Verifizierung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">5. Zahlungsabwicklung über Mollie</h5>
                <p>Zahlungen werden über die <strong>Mollie B.V. (Niederlande)</strong> abgewickelt. Es werden Name und Zahlungsdaten zur Vertragserfüllung (Art. 6 Abs. 1 lit. b DSGVO) übermittelt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. Rechtsgrundlagen der Verarbeitung</h5>
                <ul>
                    <li><strong>Art. 6 Abs. 1 lit. b DSGVO:</strong> Vertragserfüllung (Abo & App-Nutzung).</li>
                    <li><strong>Art. 9 Abs. 2 lit. h DSGVO:</strong> Verarbeitung von Sozialdaten im Gesundheitswesen.</li>
                    <li><strong>Art. 6 Abs. 1 lit. f DSGVO:</strong> Berechtigtes Interesse (Sicherheit & Protokollierung).</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">7. SSL- bzw. TLS-Verschlüsselung</h5>
                <p>Zur Sicherheit nutzen wir eine TLS 1.3 Verschlüsselung. Daten "at rest" (auf dem Server) werden mittels AES-256 geschützt.</p>
            </section>

            <section class="mb-4 bg-light p-3 border-start border-4 border-success text-success">
                <h5 class="fw-bold">8. Verzicht auf Analyse-Tools & Social Media</h5>
                <p class="mb-0">Wir verwenden kein Google Analytics, keine Werbe-Pixel und keine Social-Media-Plugins. Ihr Verhalten wird nicht getrackt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">9. Ihre Rechte (Betroffenenrechte)</h5>
                <p>Sie haben das Recht auf Auskunft, Berichtigung, Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch nach Art. 15-21 DSGVO.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">10. Auftragsverarbeitung (AVV)</h5>
                <div class="p-3 border rounded">
                    <p class="mb-0 small">Für Träger bieten wir einen digitalen <strong>AV-Vertrag nach Art. 28 DSGVO</strong> im Administrationsbereich an.</p>
                </div>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">11. Technische Sicherheit (TOM)</h5>
                <div class="row g-2 small">
                    <div class="col-md-6 border p-2"><strong>Mandantentrennung:</strong> Isolation der Datenbank-Einträge pro Träger.</div>
                    <div class="col-md-6 border p-2"><strong>Hashing:</strong> SHA-256 Versiegelung für Revisionssicherheit.</div>
                    <div class="col-md-6 border p-2"><strong>Backups:</strong> Tägliche Sicherung auf redundanten DE-Servern.</div>
                    <div class="col-md-6 border p-2"><strong>Audit-Logs:</strong> Protokollierung der Logins (Löschung nach 14 Tagen).</div>
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
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Zurück
                </a>
            </div>
        </div>
    </div>
</div>
@endsection