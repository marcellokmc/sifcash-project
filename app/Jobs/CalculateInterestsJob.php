<?php

namespace App\Jobs;

use App\Models\Adhesion;
use App\Models\Epargne;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalculateInterestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutes
    public int $backoff = 60; // 1 minute entre les tentatives

    protected $adhesionId;
    protected $calculationDate;

    public function __construct(int $adhesionId, ?string $calculationDate = null)
    {
        $this->adhesionId = $adhesionId;
        $this->calculationDate = $calculationDate ?? now()->toDateString();
        
        // Queue spécifique pour les calculs financiers
        $this->onQueue('financial-calculations');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            $adhesion = Adhesion::with(['adherent', 'plan'])->findOrFail($this->adhesionId);
            
            // Vérifier que l'adhésion est active
            if (!$adhesion->isActif()) {
                Log::info('Skipping interest calculation for inactive adhesion', [
                    'adhesion_id' => $this->adhesionId,
                    'status' => $adhesion->statut
                ]);
                return;
            }

            $calculationDate = Carbon::parse($this->calculationDate);
            $plan = $adhesion->plan;
            
            // Calculer les intérêts selon la périodicité du plan
            $interestAmount = $this->calculateInterest($adhesion, $calculationDate);
            
            if ($interestAmount > 0) {
                // Mettre à jour le solde de l'adhésion
                $adhesion->update([
                    'solde_actuel' => $adhesion->solde_actuel + $interestAmount,
                    'interets_cumules' => $adhesion->interets_cumules + $interestAmount,
                    'updated_at' => now()
                ]);

                // Créer une entrée dans les épargnes pour traçabilité
                Epargne::create([
                    'adherent_id' => $adhesion->adherent_id,
                    'adhesion_id' => $adhesion->id,
                    'type_operation' => 'interet',
                    'montant' => $interestAmount,
                    'date_operation' => $calculationDate,
                    'statut' => 'actif',
                    'description' => "Intérêts calculés pour la période - Taux: {$plan->taux_interet}%"
                ]);

                // Notifier l'adhérent si montant significatif
                if ($interestAmount >= 1000) {
                    $notificationService->sendSystemNotification(
                        $adhesion->adherent->user,
                        'interest_calculated',
                        'Intérêts crédités',
                        "Des intérêts de " . number_format($interestAmount, 0, ',', ' ') . " FCFA ont été crédités sur votre adhésion '{$plan->nom}'.",
                        [
                            'adhesion_id' => $adhesion->id,
                            'interest_amount' => $interestAmount,
                            'calculation_date' => $calculationDate->toDateString()
                        ]
                    );
                }

                Log::info('Interest calculation completed successfully', [
                    'adhesion_id' => $this->adhesionId,
                    'interest_amount' => $interestAmount,
                    'new_balance' => $adhesion->solde_actuel,
                    'calculation_date' => $calculationDate
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Interest calculation failed', [
                'adhesion_id' => $this->adhesionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Re-throw pour déclencher les tentatives
        }
    }

    /**
     * Calculer les intérêts selon la périodicité
     */
    private function calculateInterest(Adhesion $adhesion, Carbon $calculationDate): float
    {
        $plan = $adhesion->plan;
        $currentBalance = $adhesion->solde_actuel;
        $annualRate = $plan->taux_interet / 100;

        // Déterminer le taux selon la périodicité
        $rate = match ($plan->periodicite) {
            'journalier' => $annualRate / 365,
            'hebdomadaire' => $annualRate / 52,
            'mensuel' => $annualRate / 12,
            'trimestriel' => $annualRate / 4,
            'semestriel' => $annualRate / 2,
            'annuel' => $annualRate,
            default => $annualRate / 12, // Par défaut mensuel
        };

        // Intérêts simples ou composés selon le plan
        $interestAmount = $currentBalance * $rate;

        // Arrondir à 2 décimales
        return round($interestAmount, 2);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Interest calculation job failed permanently', [
            'adhesion_id' => $this->adhesionId,
            'calculation_date' => $this->calculationDate,
            'exception' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Notifier les administrateurs de l'échec
        try {
            $adhesion = Adhesion::find($this->adhesionId);
            if ($adhesion) {
                // Créer une notification d'erreur pour les admins
                app(NotificationService::class)->sendSystemNotification(
                    \App\Models\User::where('role', 'admin')->first(),
                    'calculation_error',
                    'Erreur de calcul d\'intérêts',
                    "Échec du calcul d'intérêts pour l'adhésion #{$this->adhesionId}. Intervention manuelle requise.",
                    [
                        'adhesion_id' => $this->adhesionId,
                        'error' => $exception->getMessage()
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification for job failure', [
                'error' => $e->getMessage()
            ]);
        }
    }
}