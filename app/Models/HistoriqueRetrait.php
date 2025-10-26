<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoriqueRetrait extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'demande_retrait_id',
        'adherent_id',
        'adhesion_id',
        'montant_retire',
        'mode_retrait',
        'informations_retrait',
        'date_retrait',
    ];

    protected $casts = [
        'montant_retire' => 'decimal:2',
        'informations_retrait' => 'array',
        'date_retrait' => 'datetime',
    ];

    public function demandeRetrait()
    {
        return $this->belongsTo(DemandeRetrait::class);
    }

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function adhesion()
    {
        return $this->belongsTo(Adhesion::class);
    }
}
