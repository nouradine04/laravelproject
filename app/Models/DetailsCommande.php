<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsCommande extends Model
{
    protected $table = 'details_commandes';
    protected $fillable = ['commande_id', 'burger_id', 'quantite', 'prix_unitaire'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        \Log::info('DetailsCommande model loaded'); // Log temporaire
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function burger()
    {
        return $this->belongsTo(Burger::class, 'burger_id');
    }
}