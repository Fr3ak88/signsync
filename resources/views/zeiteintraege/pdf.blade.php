<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Stundennachweis_{{ $user->name }}_{{ $monthName }}_{{ $year }}</title>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        
        .header { border-bottom: 2px solid #28a745; margin-bottom: 20px; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #28a745; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 11px; font-weight: bold; }

        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { vertical-align: top; padding: 4px 0; }
        .label { font-weight: bold; color: #666; font-size: 8px; text-transform: uppercase; }
        .value { font-size: 10px; font-weight: bold; }

        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed; }
        .data-table th { background-color: #f2f2f2; border: 1px solid #ccc; padding: 6px; text-align: left; font-size: 8px; text-transform: uppercase; }
        .data-table td { border: 1px solid #eee; padding: 6px; font-size: 9px; word-wrap: break-word; }
        .data-table tr:nth-child(even) { background-color: #fafafa; }
        .data-table tr { page-break-inside: avoid; }

        .total-row { text-align: right; margin-top: 5px; }
        .total-box { display: inline-block; background: #28a745; color: white; padding: 8px 15px; border-radius: 3px; font-size: 11px; font-weight: bold; }

        .signature-section { width: 100%; margin-top: 30px; border-collapse: collapse; table-layout: fixed; }
        .signature-box { width: 45%; vertical-align: bottom; }
        
        .signature-space { 
            height: 90px; 
            border: 1px solid #f0f0f0; 
            background-color: #fcfcfc; 
            margin-bottom: 5px; 
            text-align: center;
            vertical-align: middle;
            display: block;
        }

        .signature-line { border-top: 1px solid #333; padding-top: 5px; text-align: center; }
        
        .sig-image { 
            max-height: 80px; 
            width: auto;
            max-width: 180px;
            margin: 5px auto;
            display: block;
        }
        
        .digital-stamp { color: #004085; font-family: 'Courier', monospace; font-size: 8px; line-height: 1.2; padding: 5px; text-align: left; }
        .spacer { width: 10%; }

        .footer-note { margin-top: 40px; font-size: 7px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Leistungsnachweis Schulbegleitung</h1>
        <p>Abrechnungsmonat: {{ $monthName }} {{ $year }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <span class="label">Mitarbeiter / Begleitkraft</span><br>
                <span class="value">{{ $user->name }}</span><br>
                <small>Personal-ID: #{{ $user->id }}</small>
            </td>
            <td style="width: 50%; text-align: right;">
                <span class="label">Träger / Einrichtung</span><br>
                <span class="value">{{ $user->company ?? 'SignSync Begleitdienst' }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Datum</th>
                <th style="width: 15%;">Schüler</th>
                <th style="width: 20%;">Zeitraum</th>
                <th style="width: 10%; text-align: center;">Brutto</th>
                <th style="width: 10%; text-align: center;">Pause</th>
                <th style="width: 10%; text-align: center;">Netto</th>
                <th style="width: 25%;">Notizen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eintraege as $eintrag)
                @php 
                    $start = \Carbon\Carbon::parse($eintrag->start_zeit);
                    $ende = \Carbon\Carbon::parse($eintrag->ende_zeit);
                    $bruttoMinuten = $start->diffInMinutes($ende);
                    $pauseMinuten = $eintrag->pause_minuten ?? 0;
                    $nettoMinuten = $bruttoMinuten - $pauseMinuten;
                @endphp
                <tr>
                    <td>{{ $start->format('d.m.Y') }}</td>
                    <td>{{ $eintrag->schueler->name ?? 'N/A' }}</td>
                    <td>{{ $start->format('H:i') }} - {{ $ende->format('H:i') }}</td>
                    <td style="text-align: center;">{{ number_format($bruttoMinuten / 60, 2, ',', '.') }} h</td>
                    <td style="text-align: center; color: #666;">{{ $pauseMinuten }} Min</td>
                    <td style="text-align: center;"><strong>{{ number_format($nettoMinuten / 60, 2, ',', '.') }} h</strong></td>
                    <td><small>{{ $eintrag->notiz }}</small></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-row">
        <div class="total-box">
            Gesamtstunden (Netto): {{ number_format(floatval($totalHours), 2, ',', '.') }} Std.
        </div>
    </div>

    <table class="signature-section">
    <tr>
        {{-- Mitarbeiter --}}
        <td class="signature-box">
            <div class="signature-space">
                @if($abschluss && $abschluss->employee_signatur)
                    <img src="{{ $abschluss->employee_signatur }}" class="sig-image">
                @else
                    <div style="padding-top: 35px; color: #ddd;">(Mitarbeiter Unterschrift)</div>
                @endif
            </div>
            <div class="signature-line">
                <strong>{{ $user->name }}</strong><br>
                <small>Unterschrift Begleitkraft</small>
            </div>
        </td>

        <td class="spacer"></td>

        {{-- Schule --}}
        <td class="signature-box">
            <div class="signature-space">
                @if($abschluss && $abschluss->schule_signatur)
                    <img src="{{ $abschluss->schule_signatur }}" class="sig-image">
                @else
                    <div style="padding-top: 35px; color: #ddd;">(Stempel / Unterschrift)</div>
                @endif
            </div>
            <div class="signature-line">
                <strong>{{ $abschluss->schule_unterzeichner ?? '............................' }}</strong><br>
                <small>Bestätigung Schule / Einrichtung</small>
            </div>
        </td>
    </tr>

    @if($abschluss)
    <tr>
        <td colspan="3" style="padding-top:15px;">
            <div class="digital-stamp">
                <strong style="color:#28a745;">DIGITAL BESTÄTIGT</strong><br>
                Mitarbeiter: {{ $user->name }}<br>
                Datum: {{ \Carbon\Carbon::parse($abschluss->abgeschlossen_am)->format('d.m.Y H:i') }} Uhr<br>
                Sicherheits-ID: {{ strtoupper(substr($abschluss->file_hash, 0, 12)) }}
            </div>
        </td>
    </tr>
    @endif
</table>

    <div class="footer-note">
        Dieses Dokument wurde am {{ now()->format('d.m.Y \u\m H:i') }} Uhr erstellt.<br>
        Grundlage sind die Bestimmungen des ArbZG (§ 4). Pausen werden automatisch gemäß gesetzlicher Vorgaben (ab 6h: 30 Min, ab 9h: 45 Min) ausgewiesen.
    </div>

</body>
</html>