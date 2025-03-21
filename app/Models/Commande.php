<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = ['user_id', 'statut', 'montant_total', 'date_paiement','created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailsCommande::class);
    }
    public function burgers()
    {
        return $this->belongsToMany(Burger::class, 'commande_burger')
                    ->withPivot('quantite');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
    
}
