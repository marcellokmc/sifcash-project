<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'description',
        'periodicite',
        'montant_min',
        'montant_max',
        'taux_interet',
        'duree_min_jours',
        'duree_max_jours',
        'frais_adhesion',
        'frais_retrait',
        'actif',
        'ordre_affichage',
        'conditions'
    ];

    protected $casts = [
        'montant_min' => 'decimal:2',
        'montant_max' => 'decimal:2',
        'taux_interet' => 'decimal:2',
        'frais_adhesion' => 'decimal:2',
        'frais_retrait' => 'decimal:2',
        'actif' => 'boolean',
        'conditions' => 'array',
    ];

    // Relations
    public function adhesions()
    {
        return $this->hasMany(Adhesion::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeForPeriodicite($query, $periodicite)
    {
        return $query->where('periodicite', $periodicite);
    }

    // Méthodes utilitaires
    public function getMontantRangeAttribute()
    {
        if ($this->montant_min && $this->montant_max) {
            return number_format($this->montant_min, 0, ',', ' ') . ' - ' . number_format($this->montant_max, 0, ',', ' ') . ' FCFA';
        } elseif ($this->montant_min) {
            return 'À partir de ' . number_format($this->montant_min, 0, ',', ' ') . ' FCFA';
        } else {
            return 'Montant libre';
        }
    }

    public function getTauxInteretFormateAttribute()
    {
        return $this->taux_interet . '%';
    }

    public function getDureeFormateeAttribute()
    {
        if ($this->duree_min_jours && $this->duree_max_jours) {
            $minMois = ceil($this->duree_min_jours / 30);
            $maxMois = ceil($this->duree_max_jours / 30);
            return $minMois . ' - ' . $maxMois . ' mois';
        } elseif ($this->duree_min_jours) {
            $minMois = ceil($this->duree_min_jours / 30);
            return 'Minimum ' . $minMois . ' mois';
        } else {
            return 'Durée flexible';
        }
    }

    public function isMontantValide($montant)
    {
        if ($this->montant_min && $montant < $this->montant_min) {
            return false;
        }
        if ($this->montant_max && $montant > $this->montant_max) {
            return false;
        }
        return true;
    }

    public function canAdherer()
    {
        return $this->actif;
    }
}