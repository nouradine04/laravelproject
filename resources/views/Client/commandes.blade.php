<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes - ISI BURGER</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        h1 { text-align: center; color: #ff5722; margin-bottom: 30px; }
        .commande { background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .quantity-control { display: flex; align-items: center; gap: 10px; }
        .quantity-control button { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; }
        .buttons-container { display: flex; gap: 10px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mes Commandes</h1>

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if ($commandes->isEmpty())
            <p class="text-center">Aucune commande pour le moment.</p>
        @else
            @foreach ($commandes as $commande)
                <div class="commande" id="commande-{{ $commande->id }}">
                    <h3>Commande #{{ $commande->id }}</h3>
                    <p><strong>Montant total :</strong> <span id="montant-total-{{ $commande->id }}">{{ $commande->montant_total }}</span> €</p>
                    <p><strong>Statut :</strong> <span class="badge bg-secondary">{{ $commande->statut }}</span></p>
                    <h4>Détails de la commande :</h4>
                    <ul class="list-group mb-3">
                        @foreach ($commande->details as $detail)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="my-0">{{ $detail->burger->nom }}</h6>
                                    <small class="text-muted">Prix unitaire : {{ $detail->prix_unitaire }} €</small>
                                </div>
                                <div class="quantity-control">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity({{ $detail->id }}, {{ $commande->id }}, -1, {{ $detail->prix_unitaire }})">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <span id="quantity-{{ $detail->id }}">{{ $detail->quantite }}</span>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity({{ $detail->id }}, {{ $commande->id }}, 1, {{ $detail->prix_unitaire }})">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <span class="text-muted" id="detail-total-{{ $detail->id }}">{{ $detail->prix_unitaire * $detail->quantite }} €</span>
                            </li>
                        @endforeach
                    </ul>
                    @if ($commande->statut == 'en_attente')
                        <div class="buttons-container">
                            <form action="{{ route('client.payer', $commande->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="bi bi-credit-card"></i> Payer
                                </button>
                            </form>
                            <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i> Annuler
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="bi bi-house-door"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function updateQuantity(detailId, commandeId, change, prixUnitaire) {
            const quantityElement = document.getElementById(`quantity-${detailId}`);
            let quantity = parseInt(quantityElement.textContent);
            const newQuantity = quantity + change;
            
            // Vérifier que la quantité ne soit pas inférieure à 1
            if (newQuantity < 1) {
                return;
            }
            
            // Mettre à jour l'interface utilisateur
            quantityElement.textContent = newQuantity;
            
            // Calculer et mettre à jour le prix total de cet article
            const detailTotalElement = document.getElementById(`detail-total-${detailId}`);
            const detailTotal = (prixUnitaire * newQuantity).toFixed(2);
            detailTotalElement.textContent = `${detailTotal} €`;
            
            // Envoyer une requête AJAX pour mettre à jour la base de données
            fetch('{{ route("client.updateQuantity") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    detail_id: detailId,
                    commande_id: commandeId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le montant total de la commande
                    const montantTotalElement = document.getElementById(`montant-total-${commandeId}`);
                    montantTotalElement.textContent = data.montant_total.toFixed(2);
                } else {
                    // En cas d'erreur, rétablir l'ancienne quantité
                    quantityElement.textContent = quantity;
                    detailTotalElement.textContent = `${(prixUnitaire * quantity).toFixed(2)} €`;
                    showMessage('error', data.message || 'Une erreur est survenue.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                // En cas d'erreur, rétablir l'ancienne quantité
                quantityElement.textContent = quantity;
                detailTotalElement.textContent = `${(prixUnitaire * quantity).toFixed(2)} €`;
                showMessage('error', 'Une erreur de connexion est survenue.');
            });
        }
        
        function showMessage(type, message) {
            // Créer un élément de message
            const messageElement = document.createElement('div');
            messageElement.className = `message ${type}`;
            messageElement.textContent = message;
            
            // Ajouter le message au début du conteneur
            const container = document.querySelector('.container');
            container.insertBefore(messageElement, container.firstChild);
            
            // Supprimer le message après 3 secondes
            setTimeout(() => {
                messageElement.remove();
            }, 3000);
        }
    </script>
</body>
</html>