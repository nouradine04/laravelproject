<!DOCTYPE html>
<html>
<head>
    <title>ISI BURGER - Burgers</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .filters {
            margin-bottom: 20px;
        }
        .burger-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h2 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .card p {
            margin: 5px 0;
        }
        .card-buttons {
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }
        .btn-view {
            background-color: #007bff;
        }
        .btn-add {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nos Burgers</h1>

        <!-- Formulaire de filtres -->
        <div class="filters">
            <form method="GET" action="{{ route('burgers.index') }}">
                <label>Prix min :</label>
                <input type="number" name="prix_min" step="0.01" value="{{ request('prix_min') }}">
                <label>Prix max :</label>
                <input type="number" name="prix_max" step="0.01" value="{{ request('prix_max') }}">
                <label>Nom :</label>
                <input type="text" name="nom" value="{{ request('nom') }}">
                <button type="submit">Filtrer</button>
            </form>
        </div>

        @if ($burgers->isEmpty())
            <p>Aucun burger disponible.</p>
        @else
            <div class="burger-grid">
                @foreach ($burgers as $burger)
                    <div class="card">
                        <h2>{{ $burger->nom }}</h2>
                        <p>{{ $burger->prix }} €</p>
                        <p>Stock : {{ $burger->stock }}</p>
                        <div class="card-buttons">
                            <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-view">Voir</a>
                            <form action="{{ route('client.ajouterPanier') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                                <input type="number" name="quantite" value="1" min="1" style="width: 50px;">
                                <button type="submit" class="btn btn-add">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>