<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $fillable = [
        'user_id', 'nom', 'prenom', 'date_naissance',
        'adresse', 'telephone', 'photo', 'date_adhesion', 'solde_epargne'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_adhesion' => 'date',
        'solde_epargne' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function prets()
    {
        return $this->hasMany(Pret::class);
    }

    public function remboursements()
    {
        return $this->hasManyThrough(Remboursement::class, Pret::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function pretActif()
    {
        return $this->prets()->whereIn('statut', ['en_attente', 'approuve'])->first();
    }
}
