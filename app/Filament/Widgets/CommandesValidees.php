<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommandesValidees extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2; // Définit la largeur du widget sur 2 colonnes au lieu de 1
    }
//     public  function getColumnSpan(): int|string|array
// {
//     return ; // Prend 1/2 de la largeur pour avoir 2 widgets par ligne
// }
    protected function getStats(): array
    {
        $nombreCommandes = Commande::where('statut', 'payee')
        ->whereDate('created_at', now()->toDateString()) // Utilise uniquement la date sans l'heure
        ->count();
    

        return [
            Stat::make('Commandes validées', $nombreCommandes)
                ->description('Aujourd’hui')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([1,3,5,10,20,40])

        ];
    }
}
