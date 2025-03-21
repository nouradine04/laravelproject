<h1>Mes Commandes</h1>
@foreach ($commandes as $commande)
    <div>
        <p>Statut : {{ $commande->statut }}</p>
        <p>Montant : {{ $commande->montant_total }} €</p>
        <ul>
            @foreach ($commande->details as $detail)
                <li>{{ $detail->burger->nom }} - Quantité : {{ $detail->quantite }}</li>
            @endforeach
        </ul>
        @if ($commande->statut === 'payee')
            <a href="#">Télécharger la facture (PDF à implémenter)</a>
        @endif
    </div>
@endforeach