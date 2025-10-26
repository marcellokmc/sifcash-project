<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agence_id', 'agent_gestionnaire_id', 'membre_id', 'nom', 'prenom', 'date_naissance', 'lieu_naissance',
        'adresse', 'telephone', 'telephone_secondaire', 'email',
        'contact_urgence_nom', 'contact_urgence_prenoms', 'contact_urgence_lien_parente', 'contact_urgence_telephone',
        'contact_urgence_secondaire_nom', 'contact_urgence_secondaire_prenoms', 'contact_urgence_secondaire_lien_parente', 'contact_urgence_secondaire_telephone',
        'residence', 'secteur_numero', 'profession_exercee', 'situation_famille', 
        'profession', 'statut_compte', 'date_activation', 'suspended_at', 'suspension_reason', 'suspended_by'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_activation' => 'datetime',
        'suspended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    public function agentGestionnaire()
    {
        return $this->belongsTo(User::class, 'agent_gestionnaire_id');
    }

    /**
     * Agents gestionnaires (relation many-to-many)
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'adherent_agent', 'adherent_id', 'agent_id')
            ->withPivot('is_principal', 'notes', 'affecte_le', 'affecte_par')
            ->withTimestamps();
    }

    /**
     * Agent principal (premier agent avec is_principal = true)
     */
    public function agentPrincipal()
    {
        return $this->belongsToMany(User::class, 'adherent_agent', 'adherent_id', 'agent_id')
            ->wherePivot('is_principal', true)
            ->withPivot('is_principal', 'notes', 'affecte_le', 'affecte_par')
            ->withTimestamps()
            ->limit(1);
    }

    /**
     * Scope pour filtrer les adhérents par agent
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->whereHas('agents', function($q) use ($agentId) {
            $q->where('agent_id', $agentId);
        });
    }

    /**
     * Scope pour les adhérents non affectés
     */
    public function scopeNonAffectes($query)
    {
        return $query->whereDoesntHave('agents')
                     ->whereNull('agence_id');
    }

    public function ayantsDroit()
    {
        return $this->hasMany(AyantDroit::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function adhesions()
    {
        return $this->hasMany(Adhesion::class);
    }

    // Génération automatique du membre_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adherent) {
            if (empty($adherent->membre_id)) {
                $lastAdherent = self::orderBy('id', 'desc')->first();
                $nextId = $lastAdherent ? intval(substr($lastAdherent->membre_id, 7)) + 1 : 1;
                $adherent->membre_id = 'SIFBF-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isActif()
    {
        return $this->statut_compte === 'actif';
    }

    public function isEnAttente()
    {
        return $this->statut_compte === 'en_attente_de_verification';
    }
    
    public function credits()
    {
        return $this->hasMany(Credit::class);
    }
    
    public function epargnes()
    {
        return $this->hasMany(Epargne::class);
    }
    
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
    
    public function demandeRetraits()
    {
        return $this->hasMany(DemandeRetrait::class);
    }
    /**
     * Accesseur pour la date d'activation formatée
     */
    public function getDateActivationFormateeAttribute()
    {
        return $this->date_activation 
            ? $this->date_activation->format('d/m/Y H:i')
            : 'Non activé';
    }

    /**
     * Accesseur pour la date de naissance formattée
     */
    public function getDateNaissanceFormateeAttribute()
    {
        return $this->date_naissance->format('d/m/Y');
    }
    
    /**
     * Accesseur pour le solde total d'épargne (calculé dynamiquement)
     */
    public function getSoldeEpargneAttribute()
    {
        return $this->epargnes()->where('statut', 'actif')->sum('solde_actuel') ?? 0;
    }
    
    /**
     * Obtenir le nombre de comptes épargne actifs
     */
    public function getNombreComptesEpargneAttribute()
    {
        return $this->epargnes()->where('statut', 'actif')->count();
    }

    // Accesseurs pour la rétrocompatibilité des noms de champs de contact d'urgence
    public function getContactUrgenceLienAttribute()
    {
        return $this->contact_urgence_lien_parente;
    }

    public function getContactUrgence2NomAttribute()
    {
        return $this->contact_urgence_secondaire_nom;
    }

    public function getContactUrgence2LienAttribute()
    {
        return $this->contact_urgence_secondaire_lien_parente;
    }

    public function getContactUrgence2TelephoneAttribute()
    {
        return $this->contact_urgence_secondaire_telephone;
    }

    /**
     * Relation avec l'utilisateur qui a suspendu ce compte
     */
    public function suspendedByUser()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    /**
     * Suspendre le compte adhérent
     */
    public function suspend($reason = null, $suspendedBy = null)
    {
        $this->update([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
            'suspended_by' => $suspendedBy,
            'statut_compte' => 'suspendu',
        ]);

        // Suspendre aussi le compte utilisateur lié s'il existe
        if ($this->user) {
            $this->user->suspend($reason, $suspendedBy);
        }
    }

    /**
     * Réactiver le compte adhérent
     */
    public function activate()
    {
        $this->update([
            'suspended_at' => null,
            'suspension_reason' => null,
            'suspended_by' => null,
            'statut_compte' => 'actif',
        ]);

        // Réactiver aussi le compte utilisateur lié s'il existe
        if ($this->user) {
            $this->user->activate();
        }
    }

    /**
     * Vérifier si le compte est suspendu
     */
    public function isSuspended()
    {
        return !is_null($this->suspended_at);
    }
}
