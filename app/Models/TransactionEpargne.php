<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionEpargne extends Model
{
    /**
     * Les types d'opérations possibles
     */
    public const TYPES_OPERATION = [
        'depot' => 'Dépôt',
        'retrait' => 'Retrait',
        'interet' => 'Intérêts',
        'frais' => 'Frais',
        'virement' => 'Virement',
        'autre' => 'Autre',
    ];
    
    /**
     * Les moyens de paiement possibles
     */
    public const MOYENS_PAIEMENT = [
        'espece' => 'Espèces',
        'cheque' => 'Chèque',
        'virement' => 'Virement bancaire',
        'prelevement' => 'Prélèvement',
        'carte' => 'Carte bancaire',
        'interet' => 'Intérêts',
        'autre' => 'Autre',
    ];
    
    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'epargne_id',
        'type_operation',
        'montant',
        'date_operation',
        'moyen_paiement',
        'reference',
        'notes',
        'solde_apres_operation',
        'auteur_id',
    ];
    
    /**
     * Les attributs qui doivent être transformés.
     *
     * @var array
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'solde_apres_operation' => 'decimal:2',
        'date_operation' => 'datetime',
    ];
    
    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_operation',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'transactions_epargne';
    
    /**
     * Obtenir le compte épargne associé à la transaction.
     */
    public function epargne(): BelongsTo
    {
        return $this->belongsTo(Epargne::class);
    }
    
    /**
     * Obtenir l'utilisateur auteur de la transaction.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }
    
    /**
     * Obtenir le type d'opération formaté.
     *
     * @return string
     */
    public function getTypeOperationFormattedAttribute(): string
    {
        return self::TYPES_OPERATION[$this->type_operation] ?? $this->type_operation;
    }
    
    /**
     * Obtenir le moyen de paiement formaté.
     *
     * @return string
     */
    public function getMoyenPaiementFormattedAttribute(): string
    {
        return self::MOYENS_PAIEMENT[$this->moyen_paiement] ?? $this->moyen_paiement;
    }
    
    /**
     * Obtenir la classe CSS pour le type d'opération.
     *
     * @return string
     */
    public function getTypeOperationClassAttribute(): string
    {
        $classes = [
            'depot' => 'success',
            'retrait' => 'danger',
            'interet' => 'info',
            'frais' => 'warning',
            'virement' => 'primary',
        ];
        
        return $classes[$this->type_operation] ?? 'secondary';
    }
    
    /**
     * Obtenir le signe du montant (+ ou -).n     *
     * @return string
     */
    public function getSigneMontantAttribute(): string
    {
        return in_array($this->type_operation, ['depot', 'interet', 'virement']) ? '+' : '-';
    }
    
    /**
     * Obtenir le montant formaté avec le signe.
     *
     * @return string
     */
    public function getMontantFormattedAttribute(): string
    {
        return $this->signe_montant . ' ' . number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
    
    /**
     * Obtenir le solde après opération formaté.
     *
     * @return string
     */
    public function getSoldeApresOperationFormattedAttribute(): string
    {
        return number_format($this->solde_apres_operation, 0, ',', ' ') . ' FCFA';
    }
    
    /**
     * Le "boot" du modèle.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($transaction) {
            // Mettre à jour la date de dernière opération sur le compte épargne
            $transaction->epargne->update([
                'date_derniere_operation' => now(),
            ]);
        });
    }
}
