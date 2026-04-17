@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-2"><i class="bi bi-file-earmark-medical me-2"></i>Technischer Transparenzbericht</h1>
            <p class="text-muted mb-5 small">Stand: April 2024 | SignSync Sicherheitsarchitektur</p>

            <section class="mb-5">
                <h5 class="fw-bold"><i class="bi bi-layers me-2"></i>1. Logische Mandantentrennung</h5>
                <p>SignSync nutzt eine strikte logische Trennung der Datenbestände. Jede Organisation (Träger) erhält eine eindeutige <code>tenant_id</code>.</p>
                <div class="p-3 bg-light border rounded">
                    <ul class="mb-0 small">
                        <li><strong>Abfrage-Sicherheit:</strong> Jede Datenbankabfrage ist fest an die Session-ID und die Mandanten-ID des angemeldeten Benutzers gebunden.</li>
                        <li><strong>Cross-Tenant-Schutz:</strong> Ein Zugriff auf Daten fremder Organisationen ist durch serverseitige Validierung (Middleware) technisch ausgeschlossen.</li>
                    </ul>
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold"><i class="bi bi-patch-check me-2"></i>2. Revisionssicherheit (SHA-256)</h5>
                <p>Um die Anforderungen der Leistungsträger (Ämter) an die Unveränderbarkeit digitaler Belege zu erfüllen, setzen wir kryptografische Verfahren ein:</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border h-100 shadow-sm">
                            <h6 class="fw-bold small">Hashing-Verfahren</h6>
                            <p class="small text-muted">Bei Abschluss eines Monatsberichts wird ein digitaler Fingerabdruck (SHA-256 Hash) generiert. Jede nachträgliche Änderung am Datensatz würde die Validität des Hash-Werts zerstören.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border h-100 shadow-sm">
                            <h6 class="fw-bold small">Manipulationsschutz</h6>
                            <p class="small text-muted">Die generierte <strong>SignSync-ID</strong> auf dem PDF-Export ermöglicht es Ämtern, die Echtheit des Dokuments bei Rückfragen zweifelsfrei zu verifizieren.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold"><i class="bi bi-hdd-network me-2"></i>3. Infrastruktur & Resilienz</h5>
                <p>Der Betrieb erfolgt auf dedizierten Serverressourcen der <strong>1blu AG in Berlin</strong>.</p>
                <table class="table table-sm table-bordered small mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Maßnahme</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Backups</strong></td>
                            <td>Tägliche, AES-verschlüsselte Sicherung auf geografisch getrennten Backup-Clustern innerhalb Deutschlands.</td>
                        </tr>
                        <tr>
                            <td><strong>Verschlüsselung</strong></td>
                            <td>Datenübertragung via TLS 1.3 (High-Grade Encryption). Passwörter werden mit <code>Bcrypt</code> gehasht.</td>
                        </tr>
                        <tr>
                            <td><strong>Verfügbarkeit</strong></td>
                            <td>99,9% Verfügbarkeit im Jahresmittel durch redundante Anbindung.</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold"><i class="bi bi-pen me-2"></i>4. Digitale Signatur (Canvas)</h5>
                <p>Die Unterschriftenerfassung erfolgt direkt im Browser mittels HTML5-Canvas-Technologie.</p>
                <ul class="small">
                    <li>Es werden keine biometrischen Daten (wie Druckstärke oder Schreibgeschwindigkeit) erfasst, die über die visuelle Darstellung der Signatur hinausgehen.</li>
                    <li>Die Signatur wird als Base64-kodierte Bilddatei verschlüsselt in der Datenbank abgelegt und ist untrennbar mit dem jeweiligen Leistungsnachweis verknüpft.</li>
                </ul>
            </section>

            <section class="mb-5">
                <h5 class="fw-bold"><i class="bi bi-trash3 me-2"></i>5. Löschkonzept & Fristen</h5>
                <p>Wir unterstützen das "Recht auf Vergessenwerden" unter Einhaltung gesetzlicher Pflichten:</p>
                <div class="alert alert-warning small">
                    <strong>Wichtiger Hinweis:</strong> Da Leistungsnachweise im Sozialbereich als Buchungsbelege gelten, unterliegen sie der 10-jährigen Aufbewahrungsfrist nach § 147 AO. Innerhalb dieser Frist ist eine Löschung der Belege durch den Dienstleister rechtlich nicht zulässig.
                </div>
            </section>

            <div class="mt-5 pt-3 border-top text-center">
                <p class="small text-muted">Haben Sie spezifische Fragen zur IT-Sicherheit? Kontaktieren Sie uns unter <a href="mailto:info@signsync.de">info@signsync.de</a></p>
                <a href="{{ url()->previous() }}" class="btn btn-success mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Zurück
                </a>
            </div>
        </div>
    </div>
</div>
@endsection