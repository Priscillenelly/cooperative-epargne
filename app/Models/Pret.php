<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pret extends Model
{
    protected $fillable = [
        'membre_id', 'montant_demande', 'montant_accorde', 'motif',
        'taux_interet', 'duree_mois', 'montant_total', 'capital_restant',
        'statut', 'motif_rejet'
    ];

    protected $casts = [
        'montant_demande' => 'decimal:2',
        'montant_accorde' => 'decimal:2',
        'taux_interet' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'capital_restant' => 'decimal:2',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function remboursements()
    {
        return $this->hasMany(Remboursement::class);
    }
}
