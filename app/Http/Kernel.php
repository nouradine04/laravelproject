<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'isAdmin' => \App\Http\Middleware\IsAdmin::class,

        // Ajoute d'autres middlewares ici si nécessaire
    ];
}
