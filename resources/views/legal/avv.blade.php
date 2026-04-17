@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-lg p-5">
            
            <div class="text-center mb-5 border-bottom pb-4">
                <h1 class="fw-bold text-success">Auftragsverarbeitungsvertrag (AVV)</h1>
                <p class="text-muted">gemäß Art. 28 Abs. 3 Datenschutz-Grundverordnung (DSGVO)</p>
            </div>

            <div class="avv-content text-start small" style="line-height: 1.6;">
                
                <section class="mb-4">
                    <h5 class="fw-bold">Zwischen</h5>
                    <p class="mb-1"><strong>Dem Auftraggeber:</strong></p>
                    <p class="border-start border-3 ps-3 italic">
                    {{ $organization->company ?? $organization->name }}<br>
    
                    @if($organization->street)
                    {{ $organization->street }} {{ $organization->house_number }}<br>
                    {{ $organization->zip_code }} {{ $organization->city }}<br>
                    {{ $organization->country ?? 'Deutschland' }}
                    @else
                    <span class="text-danger small">[Bitte Adresse im Profil hinterlegen]</span>
                    @endif
                    </p>
                    
                    <h5 class="fw-bold mt-3">und</h5>
                    <p class="mb-1"><strong>Dem Auftragnehmer:</strong></p>
                    <p class="border-start border-3 ps-3 italic text-success">
                        SignSync.de by<br>
                        Fritzler-Solution, Eugen Fritzler<br>
                        Ahlener Str. 107, 59073 Hamm
                    </p>
                </section>

                <hr>

                <section class="mb-4">
                    <h6 class="fw-bold">§ 1 Gegenstand und Dauer der Vereinbarung</h6>
                    <p>Der Auftragnehmer stellt dem Auftraggeber die Nutzung der SaaS-Applikation "SignSync" zur digitalen Dokumentation von Leistungsnachweisen zur Verfügung. Im Rahmen dieser Nutzung verarbeitet der Auftragnehmer personenbezogene Daten für den Auftraggeber im Sinne des Art. 4 Nr. 8 und Art. 28 DSGVO.</p>
                </section>

                <section class="mb-4">
                    <h6 class="fw-bold">§ 2 Art und Zweck der Verarbeitung</h6>
                    <p>Die Verarbeitung umfasst folgende Datenkategorien:</p>
                    <ul>
                        <li><strong>Stammdaten:</strong> Namen der Schüler, Namen der Begleiter.</li>
                        <li><strong>Einsatzdaten:</strong> Zeiten, Tätigkeiten, Förderbedarfe.</li>
                        <li><strong>Signaturdaten:</strong> Handschriftliche Unterschriften (digitale Bilddaten).</li>
                    </ul>
                    <p>Der Kreis der Betroffenen umfasst Schüler, deren Erziehungsberechtigte sowie Mitarbeiter des Auftraggebers.</p>
                </section>

                <section class="mb-4">
                    <h6 class="fw-bold">§ 3 Pflichten des Auftragnehmers</h6>
                    <p>Der Auftragnehmer verarbeitet Daten ausschließlich auf Grundlage der getroffenen Vereinbarungen und nach dokumentierten Weisungen des Auftraggebers. Er gewährleistet, dass die zur Verarbeitung befugten Personen zur Vertraulichkeit verpflichtet wurden.</p>
                </section>

                <section class="mb-4 bg-light p-3 rounded border">
                    <h6 class="fw-bold">§ 4 Datensicherheit (Art. 32 DSGVO)</h6>
                    <p>Der Auftragnehmer setzt angemessene technische und organisatorische Maßnahmen um, insbesondere:</p>
                    <ul>
                        <li>Verschlüsselung der Daten (TLS 1.3 in transit, AES-256 at rest).</li>
                        <li>Strikte Mandantentrennung auf Datenbankebene.</li>
                        <li>Regelmäßige Backups und Desaster-Recovery-Pläne.</li>
                        <li>Zugriffskontrollsysteme und Protokollierung.</li>
                    </ul>
                    <p>Details zu den technischen und organisatorischen Maßnahmen (TOM) sind in der <a href="{{ route('datenschutz') }}" target="_blank">Datenschutzerklärung</a> des Auftragnehmers hinterlegt.</p>
                </section>

                <section class="mb-4">
                    <h6 class="fw-bold">§ 5 Unterauftragsverhältnisse (Subprozessoren)</h6>
                    <p>Der Auftraggeber genehmigt die Hinzuziehung folgender Subunternehmer:</p>
                    <ol>
                        <li><strong>1blu AG (Berlin, DE):</strong> Webhosting und Datenbankbetrieb.</li>
                        <li><strong>Mollie B.V. (Amsterdam, NL):</strong> Zahlungsabwicklung.</li>
                    </ol>
                </section>

                <section class="mb-4">
                    <h6 class="fw-bold">§ 6 Kontrollrechte und Mitwirkung</h6>
                    <p>Der Auftragnehmer stellt dem Auftraggeber alle erforderlichen Informationen zum Nachweis der Einhaltung der in Art. 28 DSGVO genannten Pflichten zur Verfügung und ermöglicht Überprüfungen.</p>
                </section>

                <section class="mt-5 p-4 border rounded bg-white shadow-sm">
                    <h5 class="fw-bold mb-3">Zustimmung und Vertragsabschluss</h5>
                    @if($organization && $organization->avv_accepted_at)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Dieser Vertrag wurde am <strong>{{ $organization->avv_accepted_at->format('d.m.Y H:i') }}</strong> 
                            von IP <strong>{{ $organization->avv_accepted_ip }}</strong> digital geschlossen.
                        </div>
                    @else
                        <p>Durch Klicken auf den untenstehenden Button schließen Sie diesen AVV rechtssicher mit der Fritzler-Solution ab.</p>
                        <form action="{{ route('admin.avv.accept') }}" method="POST">
                            @csrf
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="acceptCheck" id="acceptCheck" required>
                                <label class="form-check-label" for="acceptCheck">
                                    Ich bin vertretungsberechtigt für die oben genannte Organisation und akzeptiere den AVV.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 shadow">
                                <i class="bi bi-pen me-2"></i> AVV jetzt rechtssicher unterzeichnen
                            </button>
                        </form>
                    @endif
                </section>

            </div>

            <div class="mt-5 text-center pt-3 border-top">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Zurück zum Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection