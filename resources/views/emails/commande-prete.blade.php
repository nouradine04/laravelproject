<h1>Votre commande est prête !</h1>
<p>Bonjour {{ $commande->user->name }},</p>
<p>Votre commande #{{ $commande->id }} est prête. Veuillez trouver la facture en pièce jointe.</p>
<p>Montant total : {{ number_format($commande->montant_total, 2) }} €</p>
<p>Merci de votre confiance !</p>