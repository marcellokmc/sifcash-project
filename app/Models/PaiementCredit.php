<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaiementCredit extends Model
{
    protected $fillable = [
        'credit_id',
        'echeance_credit_id',
        'date_paiement',
        'montant',
        'penalite',
        'mode',
        'reference',
        'received_by_agent_id',
        'statut',
        'motif_rejet',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
        'penalite' => 'decimal:2',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }

    public function echeance(): BelongsTo
    {
        return $this->belongsTo(EcheanceCredit::class, 'echeance_credit_id');
    }

    public function preuves(): HasMany
    {
        return $this->hasMany(PaiementCreditPreuve::class);
    }
}
