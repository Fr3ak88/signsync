<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willkommen bei SignSync</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8f9fa;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e9ecef;">
        
        <h2 style="color: #0d6efd; margin-top: 0;">Hallo {{ $name }},</h2>
        
        <p>herzlich willkommen bei <strong>SignSync</strong>! Dein Arbeitgeber {{ $company }} hat für dich ein Profil angelegt.</p>
        
        <p>Damit du dich einloggen und deine Leistungsnachweise digital unterschreiben kannst, musst du dir nur noch ein Passwort vergeben.</p>
        
        <div style="text-align: center; margin: 35px 0;">
            <a href="{{ $url }}" 
               style="background-color: #0d6efd; 
                      color: #ffffff; 
                      padding: 14px 28px; 
                      text-decoration: none; 
                      display: inline-block; 
                      border-radius: 5px; 
                      font-weight: bold; 
                      font-size: 16px;
                      box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);">
                Jetzt Passwort festlegen
            </a>
        </div>

        <p style="font-size: 14px; color: #6c757d; border-top: 1px solid #eee; padding-top: 20px;">
            Falls der Button nicht funktioniert, kopiere diesen Link in deinen Browser:<br>
            <span style="word-break: break-all; color: #0d6efd;">{{ $url }}</span>
        </p>

        <p style="margin-bottom: 0;">Viel Erfolg,<br><strong>Dein SignSync Team</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #adb5bd; font-size: 12px;">
        &copy; {{ date('Y') }} SignSync.de | Alle Rechte vorbehalten.
    </div>
</body>
</html>