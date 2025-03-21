<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - ISI BURGER</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; font-family: 'Arial', sans-serif; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        h1 { text-align: center; color: #ff5722; margin-bottom: 40px; font-weight: bold; }
        .burger-card { background-color: white; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; transition: transform 0.3s; margin-bottom: 20px; }
        .burger-card:hover { transform: translateY(-5px); }
        .burger-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; }
        .burger-card h5 { color: #ff5722; margin-top: 15px; }
        .burger-card p { color: #555; }
        .burger-card .price { font-size: 1.2rem; font-weight: bold; color: #28a745; }
        .add-to-cart { width: 100%; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notre Menu</h1>

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if ($burgers->isEmpty())
            <p class="text-center">Aucun burger disponible pour le moment.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($burgers as $burger)
                    <div class="col">
                        <div class="card h-100 burger-card">
                            <img src="{{ $burger->image ? asset('storage/' . $burger->image) : 'https://via.placeholder.com/300x200' }}" 
                                 class="card-img-top" 
                                 alt="{{ $burger->nom }}">
                            <div class="card-body d-flex flex-column">
                                <h2 class="card-title">{{ $burger->nom }}</h2>
                                <p class="card-text">{{ $burger->description ?? 'Un burger délicieux.' }}</p>
                                <p class="card-text"><strong>{{ $burger->prix }} CFFA</strong></p>
                                <p class="card-text"><strong>{{ $burger->stock }} (en stock)</strong></p>
                                <div class="mt-auto d-flex justify-content-between">
                                    <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-outline-primary">Voir</a>
                                    <form action="{{ route('client.ajouterPanier') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                                        <button type="submit" class="btn btn-outline-success">Commander</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-house-door"></i> Retour à l'accueil
            </a>
            <a href="{{ route('client.ajouterPanier') }}" class="btn btn-outline-success ms-2">
                <i class="bi bi-cart"></i> Voir le panier
            </a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function addToCart(burgerId) {
            $.ajax({
                url: '/panier/ajouter/' + burgerId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantite: 1 // Quantité par défaut
                },
                success: function(response) {
                    if (response.success) {
                        alert('Burger ajouté au panier avec succès !');
                        console.log(`Burger ${burgerId} ajouté au panier`);
                    }
                },
                error: function(xhr) {
                    console.error('Erreur lors de l’ajout au panier:', xhr);
                    alert('Erreur lors de l’ajout au panier.');
                }
            });
        }
    </script>
</body>
</html>