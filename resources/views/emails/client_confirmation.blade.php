<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
</head>
<body style="font-family: Arial; background:#f9f9f9; padding:20px;">
    <div style="max-width:600px; margin:auto; background:white; padding:25px; border-radius:10px;">
        <h2 style="color:#0a74da;">Merci pour votre confiance</h2>

        <p>Bonjour <b>{{ $contact->name }} </b>,</p>

        <p>
            Nous avons bien reçu votre demande concernant :
        </p>

        <p style="font-weight:bold; background:#f0f0f0; padding:10px; border-radius:8px;">
            {{ $contact->problem }}
        </p>

        <p>
            Notre équipe vous contactera très bientôt pour vous proposer une solution adaptée.
        </p>

        <p style="margin-top:30px;">Cordialement,<br>
        <strong>ClimStream</strong></p>
    </div>
</body>
</html>
