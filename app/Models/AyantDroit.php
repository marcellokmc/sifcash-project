<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyantDroit extends Model
{
    use HasFactory;

    // Spécifier le nom de la table
    protected $table = 'ayants_droit';

    protected $fillable = [
        'adherent_id', 'nom', 'prenom', 'date_naissance', 'lien_parente',
        'contact', 'type_beneficiaire', 'statut_validation', 'validated_by_agent_id',
        'motif_rejet'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validated_by_agent_id');
    }

    public function isValide()
    {
        return $this->statut_validation === 'validé';
    }

    public function isEnAttente()
    {
        return $this->statut_validation === 'en_attente';
    }

    public function isRejete()
    {
        return $this->statut_validation === 'rejeté';
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}