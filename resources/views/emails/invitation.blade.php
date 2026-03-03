<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2>Hallo {{ $name }},</h2>
    <p>herzlich willkommen bei SignSync! Dein Arbeitgeber hat für dich ein Profil angelegt.</p>
    <p>Damit du dich einloggen und deine Leistungsnachweise digital unterschreiben kannst, musst du dir nur noch ein Passwort vergeben.</p>
    
    <p style="margin: 30px 0;">
        <a href="{{ $url }}" class="button" style="color: #ffffff;">Jetzt Passwort festlegen</a>
    </p>

    <p>Falls der Button nicht funktioniert, kopiere diesen Link in deinen Browser:<br>
    {{ $url }}</p>

    <p>Viel Erfolg,<br>Dein SignSync Team</p>
</body>
</html>