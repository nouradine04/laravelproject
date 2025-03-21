<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISI BURGER - Accueil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            background-color: #e74c3c !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 600;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
        }
        .nav-link:hover {
            color: #f1c40f !important;
        }
        .header {
            background-image: url('https://source.unsplash.com/1600x900/?burger,restaurant');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .header h1 {
            font-size: 4.5rem;
            font-family: 'Playfair Display', serif;
            animation: fadeIn 2s ease-in-out;
            z-index: 1;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #e74c3c;
        }
        .card-text {
            color: #666;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
        .footer a {
            color: #f1c40f;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .footer a:hover {
            color: #e67e22;
        }
        .social-icons {
            margin-top: 20px;
        }
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #f1c40f;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">ISI BURGER</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
                    @endguest
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('client.commandes') }}">Mes Commandes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('menu') }}">Nos menus</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="background: none; border: none; cursor: pointer;">Se déconnecter</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="header">
        <h1>Découvrez nos Burgers</h1>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        @if ($burgers->isEmpty())
            <p class="text-center">Aucun burger disponible pour le moment.</p>
        @else
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($burgers as $burger)
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ $burger->image ? asset('storage/' . $burger->image) : 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $burger->nom }}">
                            <div class="card-body">
                                <h2 class="card-title">{{ $burger->nom }}</h2>
                                <p class="card-text">{{ $burger->description ?? 'Un burger délicieux.' }}</p>
                                <p class="card-text"><strong>{{ $burger->prix }} CFFA</strong></p>
                                <p class="card-text"><strong>{{ $burger->stock }} (en stock)</strong></p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('burgers.show', $burger->id) }}" class="btn btn-primary">Voir</a>
                                    <form action="{{ route('client.ajouterPanier') }}" method="POST">
                                          @csrf
                                      <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                                     <button type="submit" class="btn btn-success">Commander</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer class="footer text-center">
        <div class="container">
            <p>© 2025 ISI BURGER. Tous droits réservés.</p>
            <p><a href="#services">Services</a> | <a href="#contact">Contact</a></p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

</html>