<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - ISI BURGER</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .container { width: 80%; margin: 20px auto; }
        h1 { text-align: center; color: #ff5722; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Facture - ISI BURGER</h1>
        <div class="info">
            <p><strong>Commande #{{ $commande->id }}</strong></p>
            <p>Client : {{ $commande->user->name }}</p>
            <p>Email : {{ $commande->user->email }}</p>
            <p>Date : {{ $commande->created_at->format('d/m/Y H:i') }}</p>
            <p>Statut : {{ $commande->statut }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commande->details as $detail)
                    <tr>
                        <td>{{ $detail->burger->nom }}</td>
                        <td>{{ $detail->quantite }}</td>
                        <td>{{ $detail->prix_unitaire }} €</td>
                        <td>{{ $detail->prix_unitaire * $detail->quantite }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Montant total : {{ $commande->montant_total }} €</p>
    </div>
</body>
</html>
