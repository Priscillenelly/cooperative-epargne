<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remboursement extends Model
{
    protected $fillable = [
        'pret_id', 'montant', 'capital_restant', 'date_remboursement', 'commentaire'
    ];

    protected $casts = [
        'date_remboursement' => 'date',
        'montant' => 'decimal:2',
        'capital_restant' => 'decimal:2',
    ];

    public function pret()
    {
        return $this->belongsTo(Pret::class);
    }
}
