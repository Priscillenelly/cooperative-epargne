<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    protected $fillable = [
        'membre_id', 'mois', 'annee', 'montant', 'statut', 'date_paiement'
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}
