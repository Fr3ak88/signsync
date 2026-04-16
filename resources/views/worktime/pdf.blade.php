<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #17a2b8; padding-bottom: 10px; }
        .header h2 { color: #17a2b8; margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px 8px; text-align: left; color: #444; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        td { border: 1px solid #dee2e6; padding: 8px; vertical-align: top; }
        
        .total-row { background-color: #f8f9fa; font-weight: bold; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 50px; }
        .signature-wrapper { margin-top: 20px; }
        .signature-image { width: 200px; height: auto; display: block; margin-bottom: 5px; }
        .signature-line { border-top: 1px solid #333; width: 250px; padding-top: 5px; }
        .info-text { font-size: 8px; color: #777; margin-top: 5px; line-height: 1.2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Interner Arbeitszeitnachweis</h2>
        <p><strong>Zeitraum:</strong> {{ $monthName }} {{ $year }} &nbsp;&nbsp; | &nbsp;&nbsp; <strong>Mitarbeiter:</strong> {{ $user->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Datum</th>
                <th style="width: 18%;">Zeitraum</th>
                <th style="width: 35%;">Tätigkeit / Notiz</th>
                <th style="width: 10%;" class="text-center">Brutto</th>
                <th style="width: 10%;" class="text-center">Pause</th>
                <th style="width: 15%;" class="text-right">Netto h</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eintraege as $e)
                @php
                    $start = \Carbon\Carbon::parse($e->start_zeit);
                    $ende = \Carbon\Carbon::parse($e->ende_zeit);
                    $bruttoMinuten = $start->diffInMinutes($ende);
                    $pauseMinuten = $e->pause_minuten ?? 0;
                    $nettoStunden = ($bruttoMinuten - $pauseMinuten) / 60;
                @endphp
                <tr>
                    <td>{{ $start->format('d.m.Y') }}</td>
                    <td>{{ $start->format('H:i') }} - {{ $ende->format('H:i') }}</td>
                    <td>{{ $e->notiz ?: '-' }}</td>
                    <td class="text-center">{{ number_format($bruttoMinuten / 60, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $pauseMinuten }}m</td>
                    <td class="text-right"><strong>{{ number_format($nettoStunden, 2, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Gesamtsumme Netto-Arbeitsstunden:</td>
                <td class="text-right" style="color: #17a2b8;">{{ $totalHours }} h</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; padding: 0; width: 60%;">
                    <div class="signature-wrapper">
                        @if($signature)
                            <img src="{{ $signature }}" class="signature-image">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                        <div class="signature-line">
                            Unterschrift Mitarbeiter ({{ $user->name }})
                        </div>
                    </div>
                </td>
                <td style="border: none; text-align: right; vertical-align: bottom; padding: 0; width: 40%;">
                    <p class="info-text">Erstellt am {{ now()->format('d.m.Y H:i') }} Uhr.</p>
                    <p class="info-text">Pausenregelung gemäß ArbZG § 4 berücksichtigt.</p>
                    <p class="info-text">Sicherheits-ID: {{ strtoupper(substr($abschluss->file_hash ?? 'N/A', 0, 12)) }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>