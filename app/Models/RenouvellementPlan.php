<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenouvellementPlan extends Model
{
    use HasFactory;
    
    protected $table = 'renouvellements_plans';

    protected $fillable = [
        'adhesion_id',
        'montant_souscrit',
        'date_renouvellement',
        'date_debut_periode',
        'date_fin_periode',
        'taux_interet_applique',
        'interets_generes',
        'statut',
        'commentaire',
        'effectue_par_agent_id'
    ];

    protected $casts = [
        'montant_souscrit' => 'decimal:2',
        'taux_interet_applique' => 'decimal:2',
        'interets_generes' => 'decimal:2',
        'date_renouvellement' => 'date',
        'date_debut_periode' => 'date',
        'date_fin_periode' => 'date',
    ];

    // Relations
    public function adhesion()
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function effectueParAgent()
    {
        return $this->belongsTo(User::class, 'effectue_par_agent_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeForAdhesion($query, $adhesionId)
    {
        return $query->where('adhesion_id', $adhesionId);
    }

    // Méthodes utilitaires
    public function isActif()
    {
        return $this->statut === 'actif';
    }

    public function isTermine()
    {
        return $this->statut === 'termine';
    }

    public function getDureePeriodeAttribute()
    {
        $debut = \Carbon\Carbon::parse($this->date_debut_periode);
        $fin = \Carbon\Carbon::parse($this->date_fin_periode);
        
        return $debut->diffInDays($fin);
    }

    public function getInteretsTheoriquesAttribute()
    {
        $tauxJournalier = $this->taux_interet_applique / 365 / 100;
        $duree = $this->getDureePeriodeAttribute();
        
        return $this->montant_souscrit * $tauxJournalier * $duree;
    }
}