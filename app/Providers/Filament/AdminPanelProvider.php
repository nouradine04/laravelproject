<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages;
use Filament\Widgets;
use App\Http\Middleware\IsAdmin;
use App\Filament\Admin\Resources\BurgerResource; 
use App\Filament\Admin\Resources\CommandeResource; 
use App\Filament\Admin\Resources\PaiementResource; 
use App\Filament\Admin\Resources\UserResource; 
use App\Filament\Widgets\CommandesEnCours;
use App\Filament\Widgets\CommandesValidees;
use App\Filament\Widgets\RecettesJournalieres;
use App\Filament\Widgets\CommandesParMois;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login() 
            ->pages([
                Pages\Dashboard::class,
                                ])
            ->widgets([
                Widgets\AccountWidget::class,
                CommandesEnCours::class,
                CommandesValidees::class,
                RecettesJournalieres::class,
                CommandesParMois::class,
            ])
            ->resources([ 
                BurgerResource::class,
                CommandeResource::class,
                PaiementResource::class,
                UserResource::class,
            ])
            ->middleware([ // Middleware global pour le panneau
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
            ])
            ->authMiddleware([ 
                \Filament\Http\Middleware\Authenticate::class, // Authentification de Filament
                \Filament\Http\Middleware\AuthenticateSession::class, 
                IsAdmin::class, 
            ]);
    }
}