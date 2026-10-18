<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'membre_id', 'type', 'montant', 'description', 'date_transaction'
    ];

    protected $casts = [
        'date_transaction' => 'date',
        'montant' => 'decimal:2',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}
