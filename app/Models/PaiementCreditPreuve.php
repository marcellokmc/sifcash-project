<?php

// Removed in simplified credits module

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementCreditPreuve extends Model
{
    protected $fillable = [
        'paiement_credit_id',
        'type',
        'path',
        'original_name',
    ];

    public function paiement(): BelongsTo
    {
        return $this->belongsTo(PaiementCredit::class, 'paiement_credit_id');
    }
}
