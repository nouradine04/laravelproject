<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\DetailsCommande;
use App\Models\Burger;



class CommandeController extends Controller
{
    public function index()
    {
        $commandes = auth()->user()->commandes()->with('details.burger')->get();
        return view('commandes.index', compact('commandes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'burger_id' => 'required|exists:burgers,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $burger = Burger::find($request->burger_id);
        if ($burger->stock < $request->quantite) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $montant_total = $burger->prix * $request->quantite;

        $commande = Commande::create([
            'user_id' => auth()->id(),
            'montant_total' => $montant_total,
            'statut' => 'en_attente',
        ]);

        DetailsCommande::create([
            'commande_id' => $commande->id,
            'burger_id' => $burger->id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $burger->prix,
        ]);

        $burger->stock -= $request->quantite;
        $burger->save();

        return redirect()->route('commandes.index')->with('success', 'Commande passée avec succès !');
    }
    public function updateQuantity(Request $request)
{
    try {
        // Valider la requête
        $validated = $request->validate([
            'detail_id' => 'required|integer',
            'commande_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);
        
        // Récupérer le détail de commande
        $detail = DetailsCommande::findOrFail($validated['detail_id']);
        
        // Vérifier que le détail appartient bien à la commande spécifiée
        if ($detail->commande_id != $validated['commande_id']) {
            return response()->json([
                'success' => false,
                //'message' => 'Le détail ne correspond pas à la commande spécifiée.'
            ]);
        }
        
        // Mettre à jour la quantité
        $detail->quantite = $validated['quantity'];
        $detail->save();
        
        // Recalculer le montant total de la commande
        $commande = Commande::findOrFail($validated['commande_id']);
        $montantTotal = 0;
        
        foreach ($commande->details as $d) {
            $montantTotal += $d->prix_unitaire * $d->quantite;
        }
        
        $commande->montant_total = $montantTotal;
        $commande->save();
        
        return response()->json([
            'success' => true,
            'montant_total' => $montantTotal
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ]);
    }
}
}