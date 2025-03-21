<?php

namespace App\Filament\Admin\Resources\BurgerResource\Pages;

use App\Filament\Admin\Resources\BurgerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBurger extends CreateRecord
{
    protected static string $resource = BurgerResource::class;
}
