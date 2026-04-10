<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accès Technicien</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Bonjour {{ $name }},</h2>

    <p>Votre compte technicien a été créé avec succès.</p>

    <p><strong>Email :</strong> {{ $email }}</p>
    <p><strong>Mot de passe :</strong> {{ $password }}</p>

    <p>
        Cliquez ici pour vous connecter :
        <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
    </p>

    <p>Merci.</p>
</body>
</html>
