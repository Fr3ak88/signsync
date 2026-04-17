@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-check me-2"></i>Datenschutzerklärung & Transparenzdokumentation</h1>

            <section class="mb-4">
                <h5 class="fw-bold">1. Datenschutz auf einen Blick</h5>
                <p>Diese Erklärung gibt Aufschluss darüber, wie <strong>SignSync</strong> personenbezogene Daten verarbeitet. Wir setzen auf "Privacy by Design" – das bedeutet: keine Analyse-Tools, kein Tracking, maximale Datensparsamkeit.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">2. Verantwortliche Stelle</h5>
                <p>Fritzler-Solution, Eugen Fritzler, Ahlener Str. 107, 59073 Hamm<br>
                E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a></p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">3. Infrastruktur & Serverstandort</h5>
                <p>Unsere Applikation wird bei der <strong>1blu AG, Stromstraße 1-5, 10551 Berlin</strong> gehostet.</p>
                <ul>
                    <li><strong>Standort:</strong> Ausschließlich Deutschland.</li>
                    <li><strong>Drittlandtransfer:</strong> Explizit ausgeschlossen. Wir nutzen keine US-Cloud-Anbieter (wie AWS/Google). Sämtliche Daten verbleiben im EWR-Raum.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Granulare Zuordnung der Verarbeitungstätigkeiten</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white small">
                        <thead class="table-light">
                            <tr>
                                <th>Prozess</th>
                                <th>Datenkategorien</th>
                                <th>Rechtsgrundlage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Login & Betrieb</td>
                                <td>E-Mail, Passwort-Hash, Session-ID</td>
                                <td>Art. 6 Abs. 1 lit. b (Vertrag)</td>
                            </tr>
                            <tr>
                                <td>Leistungsnachweis</td>
                                <td>Name (Schüler), Einsatzzeiten</td>
                                <td>Art. 9 Abs. 2 lit. h (Sozialdaten)</td>
                            </tr>
                            <tr>
                                <td>Zahlungsabwicklung</td>
                                <td>Zahlungsdaten, Rechnungsadresse</td>
                                <td>Art. 6 Abs. 1 lit. b (Zahlung)</td>
                            </tr>
                            <tr>
                                <td>IT-Sicherheit</td>
                                <td>IP-Adresse, Zugriffslogs</td>
                                <td>Art. 6 Abs. 1 lit. f (Ber. Interesse)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">5. Zahlungsabwicklung via Mollie</h5>
                <p>Zahlungen werden über die <strong>Mollie B.V. (Keizersgracht 126, Amsterdam, NL)</strong> prozessiert. Mollie ist PCI-DSS zertifiziert und verarbeitet Daten gemäß EU-Sicherheitsstandards.</p>
                <p class="small text-muted">
                    Details zur Datenverarbeitung durch den Zahlungsdienstleister finden Sie unter: 
                    <a href="https://www.mollie.com/de/privacy" target="_blank" class="text-success text-decoration-none">https://www.mollie.com/de/privacy</a>
                </p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. Zusammenfassung der Rechtsgrundlagen</h5>
                <p>Wir stützen die Verarbeitung auf die Erfüllung vertraglicher Pflichten (Art. 6 I b), die Verwaltung von Sozialsystemen im Gesundheitswesen (Art. 9 II h) sowie auf unser berechtigtes Interesse an der Systemsicherheit (Art. 6 I f).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">7. SSL/TLS & Datensicherheit</h5>
                <p>SignSync nutzt TLS 1.3 zur Verschlüsselung der Übertragung. Daten auf den Servern ("at rest") werden durch AES-256 Verschlüsselung sowie strikte Verzeichnis-Zugriffsbeschränkungen geschützt.</p>
            </section>

            <section class="mb-4 bg-light p-3 border-start border-4 border-success text-success">
                <h5 class="fw-bold">8. Verzicht auf Analyse-Tools & Marketing-Cookies</h5>
                <p class="mb-0">Zum Schutz der Privatsphäre setzen wir <strong>keine</strong> Analyse-Dienste (wie Google Analytics) oder Tracking-Pixel ein. Es erfolgt keine Profilbildung.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">9. Ihre Betroffenenrechte</h5>
                <p>Ihnen stehen die Rechte auf Auskunft, Berichtigung, Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch (Art. 15-21 DSGVO) sowie ein Beschwerderecht bei der Aufsichtsbehörde (LDI NRW) zu.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">10. Auftragsverarbeitung (AVV)</h5>
                <div class="p-3 border rounded bg-white">
                    <p class="mb-0 small">Für Träger der Schulbegleitung stellen wir einen digitalen <strong>AV-Vertrag nach Art. 28 DSGVO</strong> bereit. Dieser kann im Admin-Dashboard unter "Einstellungen" rechtssicher abgeschlossen werden.</p>
                </div>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">11. Technische & Organisatorische Maßnahmen (TOM)</h5>
                <div class="row g-2 small">
                    <div class="col-md-6 border p-2 bg-light"><strong>Mandantentrennung:</strong> Isolation der Daten pro Träger-ID auf Datenbankebene.</div>
                    <div class="col-md-6 border p-2 bg-light"><strong>Hashing:</strong> SHA-256 Versiegelung zur Sicherstellung der Revisionssicherheit.</div>
                    <div class="col-md-6 border p-2 bg-light"><strong>Resilienz:</strong> Tägliche Backups mit Wiederherstellungstests (Disaster Recovery).</div>
                    <div class="col-md-6 border p-2 bg-light"><strong>Audit-Logs:</strong> Protokollierung von Administrator-Aktionen zur Missbrauchskontrolle.</div>
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