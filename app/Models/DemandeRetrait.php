<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class DemandeRetrait extends Model
{
    use HasFactory, Auditable;

    protected $guarded = [];

    protected $fillable = [
        'adherent_id',
        'adhesion_id',
        'type_retrait',
        'montant_demande',
        'mode_retrait',
        'informations_retrait',
        'statut',
        'motif_rejet',
        'date_demande',
        'date_validation',
        'validated_by_agent_id',
    ];

    protected $casts = [
        'montant_demande' => 'decimal:2',
        'informations_retrait' => 'array',
        'date_demande' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function adhesion()
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function validatedByAgent()
    {
        return $this->belongsTo(User::class, 'validated_by_agent_id');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueRetrait::class);
    }
}
