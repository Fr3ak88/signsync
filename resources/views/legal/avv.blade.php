@extends('layouts.app')

@section('content')
<div class="container my-5 py-4 bg-white shadow-sm rounded border text-start" style="max-width: 900px;">
    <h1 class="fw-bold mb-4 border-bottom pb-3 text-dark">Auftragsverarbeitungsvertrag (AVV)</h1>
    <p class="text-muted small mb-4">Gemäß Art. 28 Abs. 3 Datenschutz-Grundverordnung (DSGVO)</p>

    <section class="mb-4">
        <h5 class="fw-bold">1. Gegenstand und Dauer der Verarbeitung</h5>
        <p>Der Auftragnehmer (SignSync) stellt dem Auftraggeber (Nutzer/Firma) die SaaS-Lösung zur digitalen Mitarbeiterbegleitung und Zeiterfassung zur Verfügung. Im Rahmen dieser Bereitstellung verarbeitet der Auftragnehmer personenbezogene Daten im Auftrag des Auftraggebers. Die Laufzeit dieses Vertrages richtet sich nach der Laufzeit des Hauptvertrages (Abonnement).</p>
    </section>

    <section class="mb-4">
        <h5 class="fw-bold">2. Art und Zweck der Verarbeitung</h5>
        <p>Die Verarbeitung umfasst folgende Datenkategorien:</p>
        <ul class="list-group list-group-flush mb-3 border rounded">
            <li class="list-group-item"><strong>Stammdaten Mitarbeiter:</strong> Name, E-Mail-Adresse, Einsatzbereiche.</li>
            <li class="list-group-item"><strong>Leistungsdaten:</strong> Namen der betreuten Schüler, Einsatzzeiten, Tätigkeitsnachweise.</li>
            <li class="list-group-item"><strong>Unterschriften:</strong> Digitale Signaturen zur Verifizierung der Einsätze.</li>
        </ul>
        <p>Der Zweck der Verarbeitung ist die Dokumentation von Betreuungsleistungen und die Erstellung von Leistungsnachweisen zur Abrechnung.</p>
    </section>

    <section class="mb-4">
        <h5 class="fw-bold">3. Pflichten des Auftragnehmers (SignSync)</h5>
        <p>Der Auftragnehmer sichert zu:</p>
        <ul>
            <li>Daten ausschließlich auf Weisung des Auftraggebers zu verarbeiten.</li>
            <li>Dass die zur Verarbeitung berechtigten Personen zur Vertraulichkeit verpflichtet wurden.</li>
            <li>Geeignete technische und organisatorische Maßnahmen (TOM) nach Art. 32 DSGVO zu treffen (z. B. SSL-Verschlüsselung, regelmäßige Backups).</li>
            <li>Den Auftraggeber bei Anfragen von Betroffenen (Auskunftsrecht etc.) nach Kräften zu unterstützen.</li>
        </ul>
    </section>

    <section class="mb-4">
        <h5 class="fw-bold">4. Unterauftragsverhältnisse</h5>
        <p>Der Auftraggeber genehmigt die Hinzuziehung folgender Sub-Unternehmer:</p>
        <table class="table table-sm border mt-2">
            <thead class="bg-light">
                <tr>
                    <th>Sub-Unternehmer</th>
                    <th>Zweck</th>
                    <th>Sitz</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Stripe Payments Europe, Ltd.</td>
                    <td>Zahlungsabwicklung & Rechnungsstellung</td>
                    <td>Irland (EU)</td>
                </tr>
                <tr>
                    <td>1blu AG</td>
                    <td>Server-Infrastruktur & Datenbanken</td>
                    <td>Deutschland (EU)</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="mb-4">
        <h5 class="fw-bold">5. Ort der Verarbeitung</h5>
        <p>Die Datenverarbeitung findet grundsätzlich in Rechenzentren innerhalb der Europäischen Union (EU) statt. Eine Übermittlung in Drittstaaten erfolgt nur unter Einhaltung der gesetzlichen Bestimmungen (z. B. durch Zertifizierungen wie das Data Privacy Framework bei Stripe).</p>
    </section>

    <section class="mb-5 border-top pt-4">
        <p class="small text-muted italic">
            <strong>Hinweis zum Vertragsschluss:</strong> Dieser AVV wird wirksam, sobald der Auftraggeber bei der Registrierung die entsprechende Checkbox aktiviert und damit seine Zustimmung erklärt. Eine händische Unterschrift ist gemäß Art. 28 Abs. 9 DSGVO nicht erforderlich, sofern der Vertrag in einem elektronischen Format geschlossen wird.
        </p>
    </section>
    
    <div class="text-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Zurück</a>
    </div>
</div>
@endsection