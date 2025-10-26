<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Epargne extends Model
{
    /**
     * Les types d'épargne disponibles
     */
    public const TYPES_EPARGNE = [
        'epargne_ordinaire' => 'Épargne ordinaire',
        'epargne_jeune' => 'Épargne jeune',
        'epargne_logement' => 'Épargne logement',
        'epargne_retraite' => 'Épargne retraite',
        'epargne_scolaire' => 'Épargne scolaire',
    ];
    
    /**
     * Les statuts possibles d'un compte épargne
     */
    public const STATUTS = [
        'actif' => 'Actif',
        'inactif' => 'Inactif',
        'bloque' => 'Bloqué',
        'cloture' => 'Clôturé',
    ];
    
    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'adherent_id',
        'numero_compte',
        'type_epargne',
        'montant_initial',
        'solde_actuel',
        'taux_interet',
        'interet_cumule',
        'date_ouverture',
        'date_derniere_operation',
        'dernier_calcul_interets',
        'statut',
        'notes',
    ];
    
    /**
     * Les attributs qui doivent être transformés.
     *
     * @var array
     */
    protected $casts = [
        'montant_initial' => 'decimal:2',
        'solde_actuel' => 'decimal:2',
        'taux_interet' => 'decimal:2',
        'interet_cumule' => 'decimal:2',
        'date_ouverture' => 'date',
        'date_derniere_operation' => 'datetime',
        'dernier_calcul_interets' => 'date',
    ];
    
    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_ouverture',
        'date_derniere_operation',
        'dernier_calcul_interets',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'epargnes';
    
    /**
     * Obtenir l'adhérent propriétaire du compte épargne.
     */
    public function adherent(): BelongsTo
    {
        return $this->belongsTo(Adherent::class);
    }
    
    /**
     * Obtenir les transactions du compte épargne.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionEpargne::class)->latest();
    }
    
    /**
     * Obtenir le type d'épargne formaté.
     *
     * @return string
     */
    public function getTypeEpargneFormattedAttribute(): string
    {
        return self::TYPES_EPARGNE[$this->type_epargne] ?? $this->type_epargne;
    }
    
    /**
     * Obtenir le statut formaté.
     *
     * @return string
     */
    public function getStatutFormattedAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }
    
    /**
     * Génére un numéro de compte unique.
     *
     * @return string
     */
    public static function generateAccountNumber(): string
    {
        $prefix = 'EP' . now()->format('ym');
        $lastAccount = self::where('numero_compte', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        $number = $lastAccount 
            ? (int) substr($lastAccount->numero_compte, -4) + 1 
            : 1;
            
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Le "boot" du modèle.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->numero_compte)) {
                $model->numero_compte = self::generateAccountNumber();
            }
            
            if (empty($model->solde_actuel)) {
                $model->solde_actuel = $model->montant_initial;
            }
        });
    }
}
