<?php

namespace App\Filament\Admin\Pages;


use Filament\Pages\Page;

class Dashboard extends Page
{
    public function getColumns(): int
{
    return 8; // Augmente la largeur totale du tableau de bord
}
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
 

}
