@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-11 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-2"><i class="bi bi-shield-check me-2"></i>Datenschutzerklärung</h1>
            <p class="text-muted mb-4 small">Status: Enterprise Audit-Ready | Version 2.1 (April 2026)</p>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">1. Verantwortliche Stelle & Rollenmodell</h5>
                <p>
                    <strong>Verantwortlicher (Controller):</strong><br>
                    Fritzler-Solution, Eugen Fritzler, Ahlener Str. 107, 59073 Hamm<br>
                    E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a>
                </p>
                <div class="alert alert-light border small">
                    <strong>Rollenmodell:</strong> SignSync agiert gegenüber gewerblichen Kunden (Trägern) als <strong>Auftragsverarbeiter</strong> gemäß Art. 28 DSGVO. Für den Betrieb der Website und die Account-Verwaltung der Admins sind wir <strong>Verantwortlicher</strong> gemäß Art. 4 Abs. 7 DSGVO.
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">2. End-to-End Datenfluss-Mapping</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Datenart</th>
                                <th>Präziser Zweck</th>
                                <th>System / Ort</th>
                                <th>Rechtsgrundlage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>IP-Adresse</td>
                                <td>Security, Abuse Prevention, IT-Sicherheit</td>
                                <td>Server-Logs (1blu AG, DE)</td>
                                <td>Art. 6 (1) f (Ber. Interesse)</td>
                            </tr>
                            <tr>
                                <td>Account-Daten (Admin)</td>
                                <td>Vertragsverwaltung & Kommunikation</td>
                                <td>Datenbank (1blu AG, DE)</td>
                                <td>Art. 6 (1) b (Vertrag)</td>
                            </tr>
                            <tr>
                                <td>Schülerdaten & Zeiten</td>
                                <td>Erstellung v. Leistungsnachweisen</td>
                                <td>App-DB (1blu AG, DE)</td>
                                <td>Art. 9 (2) h (Sozialdaten)</td>
                            </tr>
                            <tr>
                                <td>Unterschrift (Canvas)</td>
                                <td>Revisionssichere Verifizierung</td>
                                <td>Storage (1blu AG, DE)</td>
                                <td>Art. 6 (1) b (Vertrag)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">3. Liste der Auftragsverarbeiter (Sub-Prozessoren)</h5>
                <p class="small">Mit allen Dienstleistern bestehen <strong>AV-Verträge nach Art. 28 DSGVO</strong>. Es besteht eine lückenlose Kette technischer Schutzmaßnahmen.</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Partner</th>
                                <th>Leistung</th>
                                <th>Standort</th>
                                <th>Drittland-Garantie (Art. 44 ff.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1blu AG</strong></td>
                                <td>Webhosting & DB-Infrastruktur</td>
                                <td>Berlin, Deutschland</td>
                                <td>Entfällt (Verarbeitung rein in EU)</td>
                            </tr>
                            <tr>
                                <td><strong>Mollie B.V.</strong></td>
                                <td>Zahlungsabwicklung</td>
                                <td>Amsterdam, NL</td>
                                <td>Entfällt (Verarbeitung rein in EU)</td>
                            </tr>
                            </tbody>
                    </table>
                </div>
                <p class="small text-success mt-2"><i class="bi bi-info-circle me-1"></i> <strong>Enterprise-Status:</strong> Derzeit findet kein Datentransfer in Drittländer (insb. USA) statt.</p>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">4. Technische & Organisatorische Maßnahmen (TOM)</h5>
                <div class="row g-3 small">
                    <div class="col-md-4">
                        <div class="card p-3 h-100 bg-light border-0">
                            <h6 class="fw-bold">Vertraulichkeit</h6>
                            <ul>
                                <li>Verschlüsselung (At-Rest: AES-256)</li>
                                <li>TLS 1.3 (In-Transit)</li>
                                <li>Rollenbasiertes Zugriffssystem (RBAC)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 h-100 bg-light border-0">
                            <h6 class="fw-bold">Integrität & Resilienz</h6>
                            <ul>
                                <li>SHA-256 Hashing der Belege</li>
                                <li>Tägliche, geografisch getrennte Backups</li>
                                <li>System-Monitoring 24/7</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 h-100 bg-light border-0">
                            <h6 class="fw-bold">Verfahren</h6>
                            <ul>
                                <li>Incident Response Prozess</li>
                                <li>IP-Anonymisierung in Logs</li>
                                <li>Regelmäßige Backup-Restore-Tests</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">5. Speicherkonzept & Löschfristen</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered small">
                        <thead class="table-light">
                            <tr>
                                <th>Datenart</th>
                                <th>Frist</th>
                                <th>Begründung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Server-Logs</td>
                                <td>14 Tage</td>
                                <td>Fehleranalyse & Security</td>
                            </tr>
                            <tr>
                                <td>Leistungsnachweise</td>
                                <td>10 Jahre</td>
                                <td>Gesetzliche Aufbewahrung (§ 147 AO)</td>
                            </tr>
                            <tr>
                                <td>Kontaktdaten (Gäste)</td>
                                <td>Nach Abschluss</td>
                                <td>Zweckerfüllung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold text-uppercase small text-muted border-bottom pb-2">6. Ihre Rechte & Kontakt</h5>
                <p class="small">Sie haben Rechte auf Auskunft, Löschung, Berichtigung und Datenübertragbarkeit. Bitte senden Sie Anfragen an <strong>info@signsync.de</strong>.</p>
                <div class="p-3 bg-light border border-success rounded text-center mt-4">
                    <p class="mb-2 small fw-bold text-success">Interessiert an weiteren technischen Details?</p>
                    <a href="{{ route('transparenz') }}" class="btn btn-success btn-sm px-4 shadow-sm">Zum technischen Transparenzbericht</a>
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