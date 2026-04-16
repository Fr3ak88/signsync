@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Fehler-Anzeige an die richtige Stelle verschoben --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">{{ __('Registrierung für SignSync') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        {{-- Firma --}}
                        <div class="row mb-3">
                            <label for="company" class="col-md-4 col-form-label text-md-end">{{ __('Firma') }}</label>
                            <div class="col-md-6">
                                <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}" required autocomplete="company" autofocus>
                                @error('company')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Vorname --}}
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Vorname') }}</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Nachname (FIXED: name="last_name") --}}
                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Nachname') }}</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Adresse --}}
                        <div class="row mb-3">
                            <label for="street" class="col-md-4 col-form-label text-md-end">{{ __('Straße & Hausnr.') }}</label>
                            <div class="col-md-4">
                                <input id="street" type="text" class="form-control @error('street') is-invalid @enderror" name="street" value="{{ old('street') }}" placeholder="Straße" required>
                                @error('street')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <input id="house_number" type="text" class="form-control @error('house_number') is-invalid @enderror" name="house_number" value="{{ old('house_number') }}" placeholder="Nr." required>
                                @error('house_number')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- PLZ & Ort --}}
                        <div class="row mb-3">
                            <label for="zip_code" class="col-md-4 col-form-label text-md-end">{{ __('PLZ & Ort') }}</label>
                            <div class="col-md-2">
                                <input id="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror" name="zip_code" value="{{ old('zip_code') }}" placeholder="PLZ" required>
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" placeholder="Ort" required>
                                @error('city')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Land --}}
                        <div class="row mb-3">
                            <label for="country" class="col-md-4 col-form-label text-md-end">{{ __('Land') }}</label>
                            <div class="col-md-6">
                                <select id="country" class="form-select @error('country') is-invalid @enderror" name="country" required>
                                    <option value="Deutschland" {{ old('country', 'Deutschland') == 'Deutschland' ? 'selected' : '' }}>Deutschland</option>
                                    <option value="Österreich" {{ old('country') == 'Österreich' ? 'selected' : '' }}>Österreich</option>
                                    <option value="Schweiz" {{ old('country') == 'Schweiz' ? 'selected' : '' }}>Schweiz</option>
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 form-check d-flex justify-content-center align-items-start">
    <div style="max-width: 400px;" class="text-center"> {{-- Hilft, den Textumbruch zu kontrollieren --}}
        <input type="checkbox" name="avv_confirm" id="avv_confirm" 
               class="form-check-input @error('avv_confirm') is-invalid @enderror" 
               style="float: none; margin-right: 0.5rem;" required>
        
        <label class="form-check-label small text-muted d-inline" for="avv_confirm">
            Ich akzeptiere den 
            <a href="{{ route('avv') }}" target="_blank" class="text-primary text-decoration-none fw-bold">Auftragsverarbeitungsvertrag (AVV)</a> 
            gemäß Art. 28 DSGVO und die 
            <a href="{{ route('datenschutz') }}" target="_blank" class="text-primary text-decoration-none fw-bold">Datenschutzerklärung</a>.
        </label>

        @error('avv_confirm')
            <span class="invalid-feedback d-block" role="alert">
                <strong>Bitte bestätigen Sie den AVV, um fortzufahren.</strong>
            </span>
        @enderror
    </div>
</div>

                        <hr class="my-4">

                        {{-- E-Mail --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Adresse') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Passwort --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Passwort') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Passwort bestätigen --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Bestätigung') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    {{ __('Konto erstellen') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection