<?php

namespace App\Filament\Admin\Resources\BurgerResource\Pages;

use App\Filament\Admin\Resources\BurgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBurgers extends ListRecords
{
    protected static string $resource = BurgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
