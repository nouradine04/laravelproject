<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use App\Models\Commande;
use App\Models\DetailsCommande;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandePayee;
use Barryvdh\DomPDF\Facade\Pdf;



class ClientController extends Controller
{
    public function panier()
    {
        $panier = session('panier', []);
        $burgers = Burger::whereIn('id', array_keys($panier))->get();
        return view('client.panier', compact('burgers', 'panier'));
    }

    public function ajouterPanier(Request $request)
    {
        $burgerId = $request->input('burger_id');
        $quantite = (int) $request->input('quantite', 1); 
        $burger = Burger::findOrFail($burgerId); 
    
        if ($quantite > $burger->stock) {
            return redirect()->route('home')->with('error', 'Quantité demandée dépasse le stock disponible.');
        }
    
        $panier = session('panier', []);
        $panier[$burgerId] = ($panier[$burgerId] ?? 0) + $quantite;
        session(['panier' => $panier]);
    
        return redirect()->route('client.panier')->with('success', 'Burger ajouté au panier !');
    }

    public function commander(Request $request)
    {
        $panier = session('panier', []);
        if (empty($panier)) {
            return redirect()->route('client.panier')->with('error', 'Panier vide.');
        }

        $burgers = Burger::whereIn('id', array_keys($panier))->get();
        $montantTotal = $burgers->sum(fn ($burger) => $burger->prix * $panier[$burger->id]);

        if (!Auth::check()) {
            // Validation pour les non-connectés
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Créer un compte
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'client',
            ]);

            Auth::login($user); // Connecter automatiquement
        }

        // Créer la commande
        $commande = Commande::create([
            'user_id' => Auth::id(),
            'montant_total' => $montantTotal,
            'statut' => 'en_attente',
        ]);

        // Ajouter les détails
        foreach ($burgers as $burger) {
            DetailsCommande::create([
                'commande_id' => $commande->id,
                'burger_id' => $burger->id,
                'quantite' => $panier[$burger->id],
                'prix_unitaire' => $burger->prix,
            ]);
        }

        session()->forget('panier');
        return redirect()->route('client.commandes')->with('success', 'Commande créée ! Vous pouvez payer en ligne ou sur place.');
    }

    public function commandes()
    {
        $commandes = Commande::where('user_id', Auth::id())->with('details.burger')->get();
        return view('client.commandes', compact('commandes'));
    }
    public function payer($id)
    {
        $commande = Commande::where('user_id', Auth::id())->findOrFail($id);
        if ($commande->statut !== 'en_attente' || $commande->paiement()->exists()) {
            return redirect()->route('client.commandes')->with('error', 'Commande déjà payée.');
        }
    
        $paiement = Paiement::create([
            'commande_id' => $commande->id,
            'montant' => $commande->montant_total,
            'date_paiement' => now(),
        ]);
    
        $commande->update(['statut' => 'payee']);
        $pdf = Pdf::loadView('pdf.facture', ['commande' => $commande]);
        //dd($pdf);
        Mail::to($commande->user->email)->send(new CommandePayee($commande, $pdf->output())); // Changé en CommandePayee
    
        return redirect()->route('client.commandes')->with('success', 'Paiement effectué !');

}
public function supprimer(Request $request)
{
    $commandeId = $request->commande_id;
    $commande = Commande::find($commandeId);
    
    if (!$commande) {
        return response()->json(['success' => false, 'message' => 'Commande non trouvée.']);
    }
    
    if ($commande->statut != 'en_attente') {
        return response()->json(['success' => false, 'message' => 'Seules les commandes en attente peuvent être supprimées.']);
    }
    
    // Supprimer la commande et ses détails
    $commande->details()->delete();
    $commande->delete();
    
    return response()->json(['success' => true]);
}
    public function destroy(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur authentifié
        if ($commande->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cette commande.');
        }

        // Vérifier que la commande est en attente
        if ($commande->statut != 'en_attente') {
            return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être supprimées.');
        }

        // Supprimer les détails de la commande et la commande
        $commande->details()->delete();
        $commande->delete();

        return redirect()->back()->with('success', 'Commande supprimée avec succès.');
    }
}