<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Auditable;

class Adhesion extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'adherent_id',
        'plan_id',
        'numero_adhesion',
        'montant_souscrit',
        'date_debut',
        'date_fin',
        'statut',
        'renouvelable',
        'solde_actuel',
        'interets_cumules',
        'montant_retrait',
        'prochaine_echeance',
        'nombre_renouvellements',
        'motif_suspension',
        'date_activation',
        'date_cloture',
        'type_cloture',
        'created_by_agent_id',
        'frais_ouverture_payes',
    ];

    protected $casts = [
        'montant_souscrit' => 'decimal:2',
        'solde_actuel' => 'decimal:2',
        'interets_cumules' => 'decimal:2',
        'montant_retrait' => 'decimal:2',
        'renouvelable' => 'boolean',
        'frais_ouverture_payes' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prochaine_echeance' => 'date',
        'date_activation' => 'datetime',
        'date_cloture' => 'datetime',
    ];

    // Boot method pour générer le numéro d'adhésion
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adhesion) {
            if (empty($adhesion->numero_adhesion)) {
                $lastAdhesion = self::orderBy('id', 'desc')->first();
                $nextId = $lastAdhesion ? intval(substr($lastAdhesion->numero_adhesion, 4)) + 1 : 1;
                $adhesion->numero_adhesion = 'ADH-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relations
    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function renouvellements()
    {
        return $this->hasMany(RenouvellementPlan::class);
    }

    public function createdByAgent()
    {
        return $this->belongsTo(User::class, 'created_by_agent_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente_activation');
    }

    public function scopeClos($query)
    {
        return $query->where('statut', 'clos');
    }

    public function scopeForAdherent($query, $adherentId)
    {
        return $query->where('adherent_id', $adherentId);
    }

    // Méthodes utilitaires
    public function isActif()
    {
        return $this->statut === 'actif';
    }

    public function isEnAttente()
    {
        return $this->statut === 'en_attente_activation';
    }

    public function isClos()
    {
        return $this->statut === 'clos';
    }

    public function isSuspendu()
    {
        return $this->statut === 'suspendu';
    }

    public function getDureeRestanteAttribute()
    {
        if (!$this->date_fin) {
            return null;
        }

        $now = Carbon::now();
        $dateFin = Carbon::parse($this->date_fin);

        return $now->diffInDays($dateFin, false); // Négatif si expiré
    }

    public function getEstExpireAttribute()
    {
        if (!$this->date_fin) {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($this->date_fin));
    }

    public function getSoldeDisponibleAttribute()
    {
        return $this->solde_actuel - $this->montant_retrait;
    }

    public function getInteretsMensuelsAttribute()
    {
        $tauxMensuel = $this->plan->taux_interet / 12 / 100;
        return $this->solde_actuel * $tauxMensuel;
    }

    public function peutEtreRenouvelee()
    {
        return $this->renouvelable && 
               !$this->isClos() && 
               !$this->isSuspendu() &&
               $this->getDureeRestanteAttribute() <= 30; // 30 jours avant expiration
    }

    public function calculerDateFin($dateDebut = null)
    {
        $dateDebut = $dateDebut ? Carbon::parse($dateDebut) : Carbon::parse($this->date_debut);
        $dureeJours = $this->plan->duree_min_jours;

        return $dateDebut->copy()->addDays($dureeJours);
    }

    // Méthodes de statut
    public function activer()
    {
        // Bloquer l'activation si les frais d'ouverture ne sont pas réglés
        if (!$this->hasFraisOuvertureComplets()) {
            throw new \RuntimeException("Activation impossible: frais d'ouverture non réglés.");
        }

        // Si la condition est satisfaite, on peut marquer le flag si ce n'est pas déjà fait
        if (!$this->frais_ouverture_payes) {
            $this->frais_ouverture_payes = true;
            $this->save();
        }

        $this->update([
            'statut' => 'actif',
            'date_activation' => now(),
            'date_fin' => $this->date_fin ?? $this->calculerDateFin(),
            'prochaine_echeance' => $this->getProchaineEcheance()
        ]);
    }

    public function cloturer($motif = null, $typeCloture = null)
    {
        $this->update([
            'statut' => 'clos',
            'date_cloture' => now(),
            'motif_suspension' => $motif,
            'type_cloture' => $typeCloture
        ]);
    }

    public function suspendre($motif)
    {
        $this->update([
            'statut' => 'suspendu',
            'motif_suspension' => $motif
        ]);
    }

    public function reprendre()
    {
        $this->update([
            'statut' => 'actif',
            'motif_suspension' => null
        ]);
    }

    private function getProchaineEcheance()
    {
        $periodicite = $this->plan->periodicite;
        $now = Carbon::now();

        switch ($periodicite) {
            case 'journalier':
                return $now->addDay();
            case 'hebdomadaire':
                return $now->addWeek();
            case 'mensuel':
                return $now->addMonth();
            case 'trimestriel':
                return $now->addMonths(3);
            case 'annuel':
                return $now->addYear();
            default:
                return $now->addMonth();
        }
    }

    // --- Règles de frais d'ouverture ---
    public function fraisOuvertureRequis(): float
    {
        $plan = $this->plan; // relation déjà définie
        $dossier = (float) ($plan->frais_dossier ?? 0);
        $entretien = (float) ($plan->frais_entretien ?? 0);
        return $dossier + $entretien;
    }

    public function totalFraisOuvertureValides(): float
    {
        // Somme des montants de détails (dossier + entretien) pour les paiements validés de catégorie 'ouverture' rattachés à cette adhésion
        $paiements = Paiement::with('details')
            ->where('adhesion_id', $this->id)
            ->where('categorie', 'ouverture')
            ->where('statut', 'validé')
            ->get();

        $total = 0.0;
        foreach ($paiements as $p) {
            foreach ($p->details as $d) {
                if (in_array($d->type_frais, ['dossier', 'entretien'])) {
                    $total += (float) $d->montant;
                }
            }
        }
        return $total;
    }

    public function hasFraisOuvertureComplets(): bool
    {
        if ($this->frais_ouverture_payes) {
            return true;
        }
        return $this->totalFraisOuvertureValides() >= $this->fraisOuvertureRequis();
    }
}