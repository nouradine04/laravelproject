<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Autorise l'accès à la page de login pour tout le monde
        if ($request->is('admin/login')) {
            return $next($request);
        }

        // Vérifie si l'utilisateur est connecté et a le rôle admin ou gestionnaire
        if (Auth::guard('web')->check() && in_array(Auth::guard('web')->user()->role, ['admin', 'gestionnaire'])) {
            return $next($request);
        }
        // Redirige les utilisateurs non autorisés vers la page d'accueil
        return redirect('/');
    }
}
