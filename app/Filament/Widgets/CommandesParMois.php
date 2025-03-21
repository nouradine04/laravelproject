<?php

namespace App\Filament\Widgets;

use App\Models\Commande;
use Filament\Widgets\ChartWidget;

class CommandesParMois extends ChartWidget
{
    protected function getColumns(): int
    {
        return 6; // Définit la largeur du widget sur 2 colonnes au lieu de 1
    }
    protected static ?string $heading = 'Commandes par mois'; // Titre du widget

    protected function getData(): array
    {
        $commandes = Commande::selectRaw("strftime('%m', created_at) as mois, COUNT(*) as total")
            ->groupBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        // Vérification des valeurs récupérées
        \Log::info('Commandes par mois:', $commandes);

        // Assurer que tous les mois sont présents
        $data = array_fill(1, 12, 0); 
        foreach ($commandes as $mois => $total) {
            $moisIndex = (int) $mois; // Convertit "01", "02", ... en 1, 2, ...
            $data[$moisIndex] = $total; 
        }

        return [
            'datasets' => [
                [
                    'label' => 'Commandes',
                    'data' => array_values($data),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Peut aussi être 'line', 'pie', etc.
    }
    public  function getColumnSpan(): int|string|array
    {
        return 'full'; // Utiliser 'full' pour occuper toute la largeur
        // Ou une valeur numérique, par exemple :
        // return 2; // Prend 2 colonnes sur 3 (si le tableau de bord a 3 colonnes)
    }
}
