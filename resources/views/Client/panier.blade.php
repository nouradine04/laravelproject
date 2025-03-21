<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - ISI BURGER</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        h1 {
            text-align: center;
            color: #e74c3c;
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in-out;
        }
        .panier-list {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .panier-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }
        .panier-item:hover {
            background-color: #f9f9f9;
        }
        .panier-item p {
            margin: 0;
            font-size: 1.1rem;
            color: #555;
        }
        .panier-item strong {
            color: #e74c3c;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-control button {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .quantity-control button:hover {
            background-color: #c0392b;
        }
        .cancel-btn {
            background-color: #e74c3c;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .cancel-btn:hover {
            background-color: #c0392b;
        }
        .total {
            text-align: right;
            font-size: 1.3rem;
            font-weight: 600;
            color: #e74c3c;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #e74c3c;
            outline: none;
        }
        .btn {
            padding: 12px 25px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #27ae60;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1rem;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .empty-cart {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-top: 30px;
        }
        .empty-cart a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .empty-cart a:hover {
            color: #c0392b;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre Panier</h1>
        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif
        @if (empty($panier) || count($panier) === 0)
            <div class="empty-cart">
                <p>Votre panier est vide.</p>
                <a href="{{ route('home') }}">Retour à l'accueil</a>
            </div>
        @else
            <div class="panier-list">
                @php
                    $total = 0;
                @endphp
                @foreach ($panier as $item)
                    @if (isset($item->burger) && isset($item->quantite))
                        @php
                            $subtotal = $item->burger->prix * $item->quantite;
                            $total += $subtotal;
                        @endphp
                        <div class="panier-item" data-id="{{ $item->id }}" data-prix="{{ $item->burger->prix }}">
                            <p>{{ $item->burger->nom }}</p>
                            <div class="quantity-control">
                                <button onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                <span id="quantity-{{ $item->id }}">{{ $item->quantite }}</span>
                                <button onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                            </div>
                            <p><strong id="subtotal-{{ $item->id }}">{{ $subtotal }} CFA</strong></p>
                            <form action="{{ route('panier.annuler', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cancel-btn">Annuler</button>
                            </form>
                        </div>
                    @else
                        <div class="panier-item">
                            <p>Article invalide</p>
                        </div>
                    @endif
                @endforeach
                <p class="total">Total : <strong id="total">{{ $total }} CFA</strong></p>
            </div>
            <form action="{{ route('client.commander') }}" method="POST">
                @csrf
                @if (!auth()->check())
                    <h2 style="color: #e74c3c; font-family: 'Playfair Display', serif; margin-bottom: 20px;">Vos informations</h2>
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe :</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                @endif
                <button type="submit" class="btn">Commander</button>
            </form>
        @endif
    </div>

    <script>
        function updateQuantity(itemId, change) {
            const quantityElement = document.getElementById(`quantity-${itemId}`);
            const subtotalElement = document.getElementById(`subtotal-${itemId}`);
            let quantity = parseInt(quantityElement.textContent);
            quantity += change;

            if (quantity < 1) quantity = 1; // Quantité minimale de 1

            quantityElement.textContent = quantity;

            const prix = parseFloat(document.querySelector(`.panier-item[data-id="${itemId}"]`).getAttribute('data-prix'));
            const newSubtotal = prix * quantity;
            subtotalElement.textContent = newSubtotal.toFixed(2); // Affichage sans "CFA" ici

            updateTotal();
        }

        function updateTotal() {
            const subtotals = document.querySelectorAll('[id^="subtotal-"]');
            let total = 0;

            subtotals.forEach(subtotalElement => {
                total += parseFloat(subtotalElement.textContent); // Parse uniquement le nombre
            });

            document.getElementById('total').textContent = total.toFixed(2) + ' CFA'; // Ajoute "CFA" au total
        }
    </script>
</body>
</html>