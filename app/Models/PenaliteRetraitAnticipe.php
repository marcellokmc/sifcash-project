<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenaliteRetraitAnticipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'pourcentage_capital_requis',
        'duree_preavis_jours',
        'taux_penalite',
        'actif',
    ];

    protected $casts = [
        'pourcentage_capital_requis' => 'decimal:2',
        'taux_penalite' => 'decimal:2',
        'duree_preavis_jours' => 'integer',
        'actif' => 'boolean',
    ];
}
