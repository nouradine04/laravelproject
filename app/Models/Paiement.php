<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['commande_id', 'montant', 'date_paiement'];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
