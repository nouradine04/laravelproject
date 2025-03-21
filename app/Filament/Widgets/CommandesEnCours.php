<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Commande;

class CommandesEnCours extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2; // Définit la largeur du widget sur 2 colonnes au lieu de 1
    }
    protected static bool $isLazy = false;
    protected static bool $isRefreshable = true;

    protected function getStats(): array
    {
        $aujourdhui = now()->startOfDay();
        $demain = now()->endOfDay(); // Pour prendre toute la journée actuelle

        // Vérifier si la requête trouve des commandes
        $commandes = Commande::where('statut','en_attente')
            ->whereBetween('created_at', [$aujourdhui, $demain])
            ->get(); // Récupère les commandes

        // Debugging (afficher les commandes dans Tinker si besoin)
        \Log::info('Commandes trouvées : ', $commandes->toArray());

        // Compter le nombre de commandes trouvées
        $nombreCommandes = $commandes->count();

        return [
            Stat::make('Commandes en cours', $nombreCommandes)
                ->description('Aujourd’hui')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([1,3,5,10,20,40])
        ];
    }
    public  function getColumnSpan(): int|string|array
{
    return 2; // Prend 1/2 de la largeur pour avoir 2 widgets par ligne
}
}
