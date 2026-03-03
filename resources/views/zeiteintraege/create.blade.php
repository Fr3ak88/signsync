@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Neuer Zeiteintrag</h1>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
	<form method="POST" action="/zeiteintraege">
    @csrf
    <div class="mb-3">
        <label class="form-label">Schüler</label>
        <select name="schueler_id" class="form-select" required>
            @foreach($schueler as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Start (Datum/Uhrzeit)</label>
            <input type="datetime-local" name="start_zeit" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Ende (Datum/Uhrzeit)</label>
            <input type="datetime-local" name="ende_zeit" class="form-control" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Zeitraum speichern</button>
</form>
</div>
@endsection
