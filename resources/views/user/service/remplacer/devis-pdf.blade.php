<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 13px;
            margin: 28px;
        }

        .top-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .top-table td {
            border: none;
            vertical-align: middle;
        }

        h1 {
            text-align: center;
            margin: 0 0 8px;
            font-size: 26px;
        }

        .reference {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 22px;
        }

        .client-box {
            margin-bottom: 20px;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 8px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        table.items th,
        table.items td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }

        table.items th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .total {
            margin-top: 18px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table class="top-table">
        <tr>
            <td width="50%">
                <img src="{{ public_path('images/logo.png') }}" width="80" alt="Logo">
            </td>
            <td width="50%" style="text-align:right;">
                <strong>Date :</strong> {{ now()->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <h1>DEVIS</h1>

    <div class="reference">
        Référence : <strong>{{ $reference }}</strong>
    </div>

    <div class="client-box">
        <strong>Client :</strong> {{ $client->prenom }} {{ $client->nom }}<br>
        <strong>Téléphone :</strong> {{ $client->telephone }}
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Machine</th>
                <th>Marque</th>
                <th>Prix (MAD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($selections as $s)
                <tr>
                    <td>{{ $s->machine->name }}</td>
                    <td>{{ $s->marque->nom }}</td>
                    <td>{{ number_format($s->marque->prix, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">
        Total : {{ number_format($total, 2) }} MAD
    </p>

</body>
</html>