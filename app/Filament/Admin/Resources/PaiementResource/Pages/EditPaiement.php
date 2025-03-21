<?php

namespace App\Filament\Admin\Resources\PaiementResource\Pages;

use App\Filament\Admin\Resources\PaiementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaiement extends EditRecord
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
