<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionEligibiliteCredit extends Model
{
    protected $fillable = [
        'type_cotisation_requise',
        'duree_minimum_anciennete',
        'montant_epargne_minimum',
        'actif',
    ];

    protected $casts = [
        'duree_minimum_anciennete' => 'integer',
        'montant_epargne_minimum' => 'decimal:2',
        'actif' => 'boolean',
    ];
}
