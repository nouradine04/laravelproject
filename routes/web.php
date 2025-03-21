<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BurgerController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ClientController;
use App\Http\Middleware\IsAdmin;
use Filament\Admin\Pages\Dashboard;




//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes pour les clients
Route::get('/', [BurgerController::class, 'index'])->name('home');
Route::get('/burgers/{burger}', [BurgerController::class, 'show'])->name('burgers.show');
Route::get('/panier', [ClientController::class, 'panier'])->name('client.panier');
Route::post('/panier/ajouter', [ClientController::class, 'ajouterPanier'])->name('client.ajouterPanier');
Route::post('/commander', [ClientController::class, 'commander'])->name('client.commander');

// Routes protégées pour les clients connectés
Route::middleware('auth')->group(function () {
    Route::get('/mes-commandes', [ClientController::class, 'commandes'])->name('client.commandes');
    Route::post('/payer/{id}', [ClientController::class, 'payer'])->name('client.payer');
});
Route::post('/client/update-quantity', [App\Http\Controllers\CommandeController::class, 'updateQuantity'])->name('client.updateQuantity');
Route::post('/commandes/supprimer', [CommandeController::class, 'supprimer'])->name('client.supprimerCommande');
Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
Route::get('/menu', [BurgerController::class, 'showMenu'])->name('menu');
// Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
//     Route::get('/admin', function () {
//         return view('filament/admin/pages/dashboard'); // Retourne la vue personnalisée
//     })->name('admin.dashboard');
// });

// Auth routes


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
