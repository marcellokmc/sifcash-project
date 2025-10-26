<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementPieceJointe extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'fichier',
        'type_document',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
