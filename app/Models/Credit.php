<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Credit extends Model
{
    protected $fillable = [
        'adherent_id',
        'montant_demande',
        'duree',
        'taux',
        'montant_accorde',
        'type_credit',
        'statut',
        'motif_rejet',
        'date_demande',
        'date_validation',
        'validated_by_agent_id',
        // new fields
        'periodicite',
        'date_debut_remboursement',
        'frais_adhesion',
        'frais_dossier',
        'taux_penalite',
        'mode_penalite',
        'etat',
        // simplified flow additions
        'motif',
        'garanties',
        'contract_path',
    ];

    protected $casts = [
        'montant_demande' => 'decimal:2',
        'montant_accorde' => 'decimal:2',
        'taux' => 'decimal:2',
        'date_demande' => 'date',
        'date_validation' => 'date',
        'date_debut_remboursement' => 'date',
        'frais_adhesion' => 'decimal:2',
        'frais_dossier' => 'decimal:2',
        'taux_penalite' => 'decimal:2',
    ];

    public function adherent(): BelongsTo
    {
        return $this->belongsTo(Adherent::class);
    }

    public function garanties(): HasMany
    {
        return $this->hasMany(CreditGarantie::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CreditDocument::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementCredit::class);
    }
}
