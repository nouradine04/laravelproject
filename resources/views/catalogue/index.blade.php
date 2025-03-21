<h1>Catalogue des Burgers</h1>
<form method="GET">
    <input type="text" name="nom" placeholder="Rechercher par nom" value="{{ request('nom') }}">
    <input type="number" name="prix_min" placeholder="Prix min" value="{{ request('prix_min') }}">
    <input type="number" name="prix_max" placeholder="Prix max" value="{{ request('prix_max') }}">
    <button type="submit">Filtrer</button>
</form>
@foreach ($burgers as $burger)
    <div>
        <h2>{{ $burger->nom }}</h2>
        <p>Prix : {{ $burger->prix }} CFFA</p>
        <a href="{{ route('catalogue.show', $burger) }}">Voir détails</a>
    </div>
@endforeach