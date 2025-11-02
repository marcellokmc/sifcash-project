<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot le trait Auditable
     */
    public static function bootAuditable()
    {
        // Enregistrer lors de la création
        static::created(function ($model) {
            static::logAudit('created', $model);
        });

        // Enregistrer lors de la mise à jour
        static::updated(function ($model) {
            static::logAudit('updated', $model);
        });

        // Enregistrer lors de la suppression
        static::deleted(function ($model) {
            static::logAudit('deleted', $model);
        });
    }

    /**
     * Enregistrer une entrée d'audit
     */
    protected static function logAudit($action, $model)
    {
        // Ne pas auditer si pas d'utilisateur connecté (seeders, jobs, etc.)
        if (!Auth::check()) {
            return;
        }

        try {
            // Déterminer la catégorie d'action basée sur le modèle
            $category = static::determineActionCategory(get_class($model));
            
            // Déterminer l'utilisateur ciblé si applicable
            $targetUserId = static::determineTargetUser($model);
            
            // Générer une description lisible de l'action
            $description = static::generateDescription($action, $model);

            Audit::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'action_category' => $category,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'target_user_id' => $targetUserId,
                'ancienne_valeur' => $action === 'updated' ? $model->getOriginal() : null,
                'nouvelle_valeur' => $model->getAttributes(),
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
                'user_agent' => request()->userAgent(),
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            // Ne pas interrompre le processus si l'audit échoue
            \Log::error('Erreur audit: ' . $e->getMessage());
        }
    }

    /**
     * Déterminer la catégorie d'action basée sur le type de modèle
     */
    protected static function determineActionCategory($modelClass)
    {
        $baseName = class_basename($modelClass);
        
        $categoryMap = [
            'Paiement' => 'paiement',
            'PaiementCredit' => 'paiement',
            'PaiementDetail' => 'paiement',
            'DemandeRetrait' => 'retrait',
            'HistoriqueRetrait' => 'retrait',
            'Adhesion' => 'adhesion',
            'Affectation' => 'affectation',
            'Adherent' => 'adherent',
            'User' => 'utilisateur',
            'Plan' => 'plan',
            'Alerte' => 'alerte',
        ];
        
        return $categoryMap[$baseName] ?? 'autre';
    }

    /**
     * Déterminer l'utilisateur ciblé par l'action
     */
    protected static function determineTargetUser($model)
    {
        // Si le modèle a un adherent_id, on cible cet adhérent
        if (isset($model->adherent_id)) {
            $adherent = \App\Models\Adherent::find($model->adherent_id);
            return $adherent ? $adherent->user_id : null;
        }
        
        // Si c'est un adhérent directement
        if ($model instanceof \App\Models\Adherent && isset($model->user_id)) {
            return $model->user_id;
        }
        
        // Si le modèle a un validated_by_agent_id, on note l'agent
        if (isset($model->validated_by_agent_id)) {
            return $model->validated_by_agent_id;
        }
        
        return null;
    }

    /**
     * Générer une description lisible de l'action
     */
    protected static function generateDescription($action, $model)
    {
        $baseName = class_basename(get_class($model));
        $actionText = [
            'created' => 'créé',
            'updated' => 'modifié',
            'deleted' => 'supprimé',
        ][$action] ?? $action;
        
        $description = "{$baseName} #{$model->id} {$actionText}";
        
        // Ajouter des détails spécifiques selon le modèle
        if ($model instanceof \App\Models\Paiement && isset($model->statut)) {
            $description .= " - Statut: {$model->statut}";
            if (isset($model->montant)) {
                $description .= " - Montant: {$model->montant}";
            }
        }
        
        if ($model instanceof \App\Models\DemandeRetrait && isset($model->statut)) {
            $description .= " - Statut: {$model->statut}";
            if (isset($model->montant_demande)) {
                $description .= " - Montant: {$model->montant_demande}";
            }
        }
        
        if ($model instanceof \App\Models\Adhesion) {
            $description .= " - N°: {$model->numero_adhesion}";
            if (isset($model->statut)) {
                $description .= " - Statut: {$model->statut}";
            }
        }
        
        return $description;
    }

    /**
     * Méthode pour enregistrer une action personnalisée
     */
    public function audit($action, $description = null, $targetUserId = null)
    {
        if (!Auth::check()) {
            return;
        }

        $category = static::determineActionCategory(get_class($this));

        Audit::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'action_category' => $category,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'target_user_id' => $targetUserId ?? static::determineTargetUser($this),
            'nouvelle_valeur' => [
                'description' => $description,
                'data' => $this->getAttributes()
            ],
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }
}
