<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'type_frais',
        'montant',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
