<?php

namespace App\Filament\Admin\Resources\BurgerResource\Pages;

use App\Filament\Admin\Resources\BurgerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBurger extends EditRecord
{
    protected static string $resource = BurgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
