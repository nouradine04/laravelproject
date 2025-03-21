<?php
namespace App\Filament\Widgets;

use App\Models\Commande;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecettesJournalieres extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2; // Définit la largeur du widget sur 2 colonnes au lieu de 1
    }
    public  function getColumnSpan(): int|string|array
{
    return 4; // Prend 1/2 de la largeur pour avoir 2 widgets par ligne
}
    protected function getStats(): array
    {
        $aujourdhui = now()->toDateString(); // Prend uniquement la date
       
        $recettes = Commande::where('statut', 'payee') // Vérifie si "statut" est correct
            ->whereDate('created_at', $aujourdhui)
            ->sum('montant_total'); // Somme du champ 'montant'

        return [
            Stat::make('Recettes journalières', number_format($recettes, 2, '.', ' ') . ' FCFA')
                ->description('Aujourd’hui')
                ->descriptionIcon('heroicon-o-currency-euro')
                ->color('primary')
                ->chart([1,3,5,10,20,40])

        ];
    }
}
