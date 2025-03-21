<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $burger->nom }} - ISI BURGER</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
        }
        .card-footer {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
        }
        .img-fluid {
            border: 2px solid #e9ecef;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header text-center bg-light">
                <h2 class="mb-0">{{ $burger->nom }}</h2>
            </div>
            <div class="card-body text-center">
                <!-- Image du burger -->
                <img src="{{ $burger->image ? asset('storage/' . $burger->image) : asset('placeholder.jpg') }}" 
                     class="img-fluid rounded mb-4" 
                     style="max-width: 300px;" 
                     alt="{{ $burger->nom }}">
                
                <!-- Description du burger -->
                <p class="lead text-muted">{{ $burger->description }}</p>

                <!-- Prix et stock -->
                <div class="mb-4">
                    <h4 class="text-success"><strong>Prix:</strong> {{ $burger->prix }} FCFA</h4>
                    <h5 class="text-info"><strong>Stock:</strong> {{ $burger->stock }}</h5>
                </div>

                <!-- Formulaire de commande -->
                <form method="POST" action="{{ route('client.commander') }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                    
                    <div class="input-group mb-3 justify-content-center">
                        <input type="number" 
                               name="quantite" 
                               min="1" 
                               max="{{ $burger->stock }}" 
                               class="form-control text-center" 
                               style="max-width: 100px;" 
                               required>
                        <button type="submit" class="btn btn-outline-success">Commander</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <a href="{{ route('menu') }}" class="btn btn-outline-secondary">Retour</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optionnel, pour les fonctionnalités interactives) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>