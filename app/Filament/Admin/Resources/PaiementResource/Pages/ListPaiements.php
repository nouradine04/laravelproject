<?php

namespace App\Filament\Admin\Resources\PaiementResource\Pages;

use App\Filament\Admin\Resources\PaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaiements extends ListRecords
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
