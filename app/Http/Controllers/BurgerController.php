<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;

class BurgerController extends Controller
{
    public function index(Request $request)
    {
        $burgers = Burger::where('archive', false)
                        ->where('stock', '>', 0)
                        ->take(6) 
                        ->get();
        return view('welcome', compact('burgers')); // Nouvelle vue index
    }

 
    public function show($id)
{
    $burger = Burger::find($id);

    if (!$burger) {
        return redirect()->route('home')->with('error', 'Article invalide');
    }

    return view('catalogue/show', compact('burger'));
}
public function showMenu()
    {
        $burgers = Burger::where('archive', false)->get(); // ou where('archive', 0)
        return view('Client/menu', compact('burgers'));
    }

}