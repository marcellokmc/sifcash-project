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
            Audit::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $action === 'updated' ? json_encode($model->getOriginal()) : null,
                'new_values' => json_encode($model->getAttributes()),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Ne pas interrompre le processus si l'audit échoue
            \Log::error('Erreur audit: ' . $e->getMessage());
        }
    }

    /**
     * Méthode pour enregistrer une action personnalisée
     */
    public function audit($action, $description = null)
    {
        if (!Auth::check()) {
            return;
        }

        Audit::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'new_values' => json_encode([
                'description' => $description,
                'data' => $this->getAttributes()
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
