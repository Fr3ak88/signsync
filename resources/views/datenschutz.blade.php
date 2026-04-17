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
                    <li><strong>Signaturdaten:</strong> Bilddaten der Handschrift (HTML5-Canvas), die zur Verifizierung verschlüsselt auf unserem Server abgelegt werden.</li>
                    <li><strong>Funktion:</strong> Speicherung von Einsatzzeiten und Schülernamen zur Erstellung von Leistungsnachweisen.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">4. Cookies</h5>
                <p>SignSync setzt <strong>ausschließlich technisch notwendige Session-Cookies</strong> ein. Wir verwenden keine Statistik- oder Marketing-Cookies.</p>
            </section>

            <section class="mb-4 border-start border-success border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-success">5. Rechtsgrundlagen der Verarbeitung</h5>
                <p>Die Verarbeitung erfolgt auf Basis folgender Grundlagen:</p>
                <ul>
                    <li><strong>Art. 6 Abs. 1 lit. b DSGVO:</strong> Erfüllung des Nutzungsvertrages bzw. Abonnements.</li>
                    <li><strong>Art. 6 Abs. 1 lit. f DSGVO:</strong> Berechtigtes Interesse an der IT-Sicherheit und dem Schutz vor Missbrauch.</li>
                    <li><strong>Art. 9 Abs. 2 lit. h DSGVO:</strong> Verarbeitung im Rahmen der Verwaltung von Sozialsystemen (Schulbegleitung).</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">6. Zweck der Verarbeitung</h5>
                <p>Die Verarbeitung dient der Erstellung manipulationssicherer Leistungsnachweise. Jeder Beleg wird kryptografisch versiegelt, um die Anforderungen an die Revisionssicherheit zu erfüllen.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">7. Weitergabe von Daten & Zahlungsabwicklung</h5>
                <p>Zahlungen werden über die <strong>Mollie B.V. (Niederlande)</strong> abgewickelt. Ein entsprechender Auftragsverarbeitungsvertrag (AVV) gemäß Art. 28 DSGVO liegt vor. Es werden nur die zur Zahlungsabwicklung zwingend erforderlichen Daten übermittelt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">8. Auftragsverarbeitung & Subprozessoren</h5>
                <p>Wir arbeiten mit der <strong>1blu AG (Berlin, DE)</strong> für das Hosting zusammen. Ein AVV gemäß Art. 28 DSGVO stellt sicher, dass Ihre Daten ausschließlich weisungsgebunden verarbeitet werden.</p>
            </section>

            <section class="mb-4 border-start border-danger border-4 ps-3 bg-light py-2">
                <h5 class="fw-bold text-danger">9. Drittlandübermittlung</h5>
                <p class="mb-0"><strong>SignSync verarbeitet Daten ausschließlich innerhalb der EU/EWR.</strong> Ein Transfer in Drittstaaten (insb. USA) findet nicht statt.</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">10. Speicherdauer</h5>
                <ul>
                    <li><strong>Server-Logs:</strong> 14 Tage.</li>
                    <li><strong>Leistungsnachweise & Signaturen:</strong> Gemäß den steuer- und handelsrechtlichen Aufbewahrungspflichten für 10 Jahre (§ 257 HGB, § 147 AO).</li>
                    <li><strong>Account-Daten:</strong> Bis zur Kündigung des Abos, sofern keine gesetzlichen Fristen entgegenstehen.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">11. Ihre Rechte als betroffene Person</h5>
                <p>Ihnen stehen die Rechte auf Auskunft, Berichtigung, Löschung, Einschränkung, Datenübertragbarkeit und Widerspruch (Art. 15-21 DSGVO) zu.</p>
            </section>

            <div class="p-3 bg-light rounded border text-center mb-4">
                <i class="bi bi-file-earmark-text me-2"></i>
                Detaillierte technische Informationen finden Sie in unserem 
                <a href="{{ url('/transparenz') }}" class="text-success fw-bold">Transparenzbericht</a>.
            </div>

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