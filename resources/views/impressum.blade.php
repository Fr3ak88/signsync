@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 card border-0 shadow-sm p-5">
            <h1 class="fw-bold text-success mb-4">Impressum</h1>

            <section class="mb-4">
                <h5 class="fw-bold">Angaben gemäß § 5 TMG</h5>
                <p>
                    <strong>SignSync.de ist ein Angebot von:</strong><br>
                    Eugen Fritzler<br>
                    Fritzler-Solution<br>
                    Ahlener Str. 107<br>
                    59073 Hamm
                </p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">Kontakt</h5>
                <p>
                    Telefon: 02381 / 9588314<br>
                    E-Mail: <a href="mailto:info@signsync.de" class="text-success">info@signsync.de</a>
                </p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">Umsatzsteuer-ID</h5>
                <p>Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
                DE346643197</p>
            </section>

            <section class="mb-4">
                <h5 class="fw-bold">Redaktionell verantwortlich</h5>
                <p>Eugen Fritzler</p>
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