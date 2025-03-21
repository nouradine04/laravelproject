<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Page;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        // Valide les champs du formulaire
       // $this->validate();

        // Tente de connecter l'utilisateur
        // if (!Auth::attempt($this->form->getState())) {
        //     throw new \Exception('Identifiants invalides.');
        // }

        // Récupère l'utilisateur connecté
        $user = Auth::user();

        // Vérifie le rôle de l'utilisateur
        if (!in_array($user->role, ['admin', 'gestionnaire'])) {
            Auth::logout(); // Déconnecte l'utilisateur
            return redirect('/'); // Redirige vers la page d'accueil
        }

        // Régénère la session et autorise l'accès
        session()->regenerate();
        return app(LoginResponse::class);
    }

}