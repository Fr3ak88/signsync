@extends('layouts.app')

@section('content')
<div class="container py-5 text-start">
    <div class="row justify-content-center">
        <div class="col-md-10 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4"><i class="bi bi-shield-check me-2"></i>Datenschutzerklärung & Transparenzbericht</h1>

            <section class="mb-4">
                <h5 class="fw-bold">1. Allgemeine Hinweise & Grundprinzipien</h5>
                <p>Diese Erklärung gibt Aufschluss über die Verarbeitung personenbezogener Daten in der Applikation <strong>SignSync</strong>. Wir arbeiten nach den Grundsätzen der Datensparsamkeit und Zweckbindung. Ein Tracking des Nutzerverhaltens findet nicht statt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">2. Verantwortliche Stelle</h5>
                <p>Fritzler-Solution, Eugen Fritzler, Ahlener Str. 107, 59073 Hamm<br>
                E-Mail: <a href="mailto:info@signsync.de" class="text-success text-decoration-none">info@signsync.de</a></p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">3. Datenerfassung auf der Website & App</h5>
                <p>Wir differenzieren die Datenerfassung nach folgenden Zwecken:</p>
                <ul>
                    <li><strong>Bereitstellung:</strong> IP-Adresse & Browserdaten (Server-Logs) zur technischen Auslieferung.</li>
                    <li><strong>Sicherheit:</strong> Protokollierung von Login-Versuchen zur Missbrauchserkennung.</li>
                    <li><strong>Funktion:</strong> Speicherung von Eingabedaten (Zeiten, Namen) zur Vertragserfüllung.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Cookies</h5>
                <p>SignSync setzt <strong>ausschließlich technisch notwendige Session-Cookies</strong> ein. Diese dienen dazu, Sie während einer aktiven Sitzung als eingeloggten Nutzer zu identifizieren. Wir verwenden keine Statistik-, Marketing- oder Drittanbieter-Cookies.</p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">5. Kontaktaufnahme</h5>
                <p class="mb-0 small">Anfragen via E-Mail werden zur Bearbeitung und für den Fall von Anschlussfragen gespeichert. Die Rechtsgrundlage hierfür ist Art. 6 Abs. 1 lit. b (vorvertragliche Maßnahmen) oder lit. f (berechtigtes Interesse).</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. Zweck der Verarbeitung</h5>
                <p>Die Verarbeitung dient primär der Erstellung digitaler Leistungsnachweise in der Schulbegleitung, der rechtssicheren Archivierung von Einsatzzeiten sowie der Abrechnungsunterstützung für soziale Träger.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">7. Weitergabe von Daten & Empfänger</h5>
                <p>Eine Weitergabe erfolgt ausschließlich an:</p>
                <ul>
                    <li>Den jeweiligen <strong>Zahlungsdienstleister</strong> (Mollie) zur Abwicklung Ihres Abonnements.</li>
                    <li>Behörden/Leistungsträger, jedoch <strong>nur durch den Nutzer selbst</strong> (Export-Funktion).</li>
                    <li>Unseren <strong>Hosting-Provider</strong> im Rahmen der Auftragsverarbeitung.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">8. Auftragsverarbeitung & Subprozessoren</h5>
                <p>Wir arbeiten mit folgenden Kern-Dienstleistern zusammen:</p>
                <div class="table-responsive small">
                    <table class="table table-bordered bg-white">
                        <thead class="table-light">
                            <tr>
                                <th>Anbieter</th>
                                <th>Sitz</th>
                                <th>Leistung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1blu AG</strong></td>
                                <td>Berlin, DE</td>
                                <td>Cloud-Hosting & Datenbankbetrieb</td>
                            </tr>
                            <tr>
                                <td><strong>Mollie B.V.</strong></td>
                                <td>Amsterdam, NL</td>
                                <td>Zahlungsabwicklung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-4 border-start border-danger border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-danger">9. Drittlandübermittlung</h5>
                <p class="mb-0"><strong>SignSync verarbeitet Daten ausschließlich innerhalb der EU/EWR.</strong> Es findet keine Übermittlung in Drittstaaten (insb. USA) statt. Wir nutzen keine US-basierten Subprozessoren, wodurch Garantien wie Standardvertragsklauseln (SCC) oder das Data Privacy Framework aufgrund der rein europäischen Datenhaltung nicht erforderlich sind.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">10. Speicherdauer</h5>
                <ul>
                    <li><strong>Server-Logs:</strong> Automatische Löschung nach 14 Tagen.</li>
                    <li><strong>Vertragsdaten:</strong> Speicherung gemäß gesetzlicher Aufbewahrungsfristen (§ 147 AO, 10 Jahre).</li>
                    <li><strong>Nutzungsdaten:</strong> Löschung unmittelbar nach Account-Deaktivierung, sofern keine gesetzlichen Pflichten entgegenstehen.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">11. Ihre Rechte als betroffene Person</h5>
                <p>Ihnen stehen die Rechte auf Auskunft, Berichtigung, Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch (Art. 15-21 DSGVO) zu. Bitte wenden Sie sich hierfür an info@signsync.de.</p>
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