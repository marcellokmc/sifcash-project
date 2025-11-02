<?php

namespace App\Observers;

use App\Models\Adherent;
use App\Services\PerformanceOptimizationService;

class ModelChangeObserver
{
    public function saved($model): void
    {
        $this->invalidateRelatedCaches($model);
    }

    public function deleted($model): void
    {
        $this->invalidateRelatedCaches($model);
    }

    private function invalidateRelatedCaches($model): void
    {
        $service = app(PerformanceOptimizationService::class);

        // Déterminer l'adhérent impacté
        $adherent = null;
        if ($model instanceof Adherent) {
            $adherent = $model;
        } elseif (property_exists($model, 'adherent_id') && $model->adherent_id) {
            $adherent = Adherent::with('agents')->find($model->adherent_id);
        } elseif (method_exists($model, 'adherent')) {
            try {
                $adherent = $model->adherent()->with('agents')->first();
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if ($adherent) {
            $service->invalidateForAdherent($adherent);
        }

        // Optionnel: invalider global si le modèle affecte les agrégats globaux
        $globalModels = [\App\Models\Adherent::class, \App\Models\Document::class, \App\Models\Credit::class, \App\Models\Epargne::class, \App\Models\Adhesion::class, \App\Models\Paiement::class, \App\Models\DemandeRetrait::class];
        foreach ($globalModels as $cls) {
            if ($model instanceof $cls) {
                $service->invalidateGlobalCache();
                break;
            }
        }
    }
}