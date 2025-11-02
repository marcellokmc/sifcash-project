<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CalculateInterestsJob;
use App\Models\Adhesion;
use App\Services\NotificationService;
use App\Services\PerformanceOptimizationService;
use Carbon\Carbon;

class DailyMaintenanceCommand extends Command
{
    protected $signature = 'sif:daily-maintenance 
                          {--dry-run : Run in simulation mode without making changes}
                          {--interests : Only calculate interests}
                          {--reminders : Only send payment reminders}
                          {--cleanup : Only perform cleanup tasks}';

    protected $description = 'Effectue les tâches de maintenance quotidiennes du système SIF';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $onlyInterests = $this->option('interests');
        $onlyReminders = $this->option('reminders');
        $onlyCleanup = $this->option('cleanup');

        $this->info('🚀 Début des tâches de maintenance quotidiennes - ' . now()->format('d/m/Y H:i:s'));
        
        if ($isDryRun) {
            $this->warn('🔍 Mode simulation activé - Aucune modification ne sera effectuée');
        }

        $results = [
            'interests_calculated' => 0,
            'reminders_sent' => 0,
            'overdue_detected' => 0,
            'cache_entries_cleaned' => 0,
            'errors' => []
        ];

        try {
            // 1. Calcul des intérêts
            if (!$onlyReminders && !$onlyCleanup) {
                $results['interests_calculated'] = $this->calculateDailyInterests($isDryRun);
            }

            // 2. Envoi des rappels de paiement (désactivé)
            if (!$onlyInterests && !$onlyCleanup) {
                $results['reminders_sent'] = 0;
            }

            // 3. Détection des impayés (désactivée)
            if (!$onlyInterests && !$onlyReminders && !$onlyCleanup) {
                $results['overdue_detected'] = 0;
            }

            // 4. Nettoyage et optimisation
            if (!$onlyInterests && !$onlyReminders) {
                $results['cache_entries_cleaned'] = $this->performCleanupTasks($isDryRun);
            }

            $this->displayResults($results);
            $this->info('✅ Maintenance quotidienne terminée avec succès');

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la maintenance quotidienne: ' . $e->getMessage());
            \Log::error('Daily maintenance failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Calculer les intérêts quotidiens
     */
    private function calculateDailyInterests(bool $isDryRun): int
    {
        $this->info('💰 Calcul des intérêts quotidiens...');

        $adhesions = Adhesion::with(['plan', 'adherent'])
            ->where('statut', 'actif')
            ->whereHas('plan', function($query) {
                $query->where('actif', true)
                      ->where('periodicite', 'journalier');
            })
            ->get();

        if ($adhesions->isEmpty()) {
            $this->info('   ℹ️  Aucune adhésion active avec périodicité journalière trouvée');
            return 0;
        }

        $this->info("   📊 {$adhesions->count()} adhésion(s) à traiter");

        $processed = 0;
        $progressBar = $this->output->createProgressBar($adhesions->count());

        foreach ($adhesions as $adhesion) {
            try {
                if (!$isDryRun) {
                    CalculateInterestsJob::dispatch($adhesion->id, now()->toDateString())
                        ->onQueue('financial-calculations');
                }
                
                $processed++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $this->error("   ❌ Erreur pour l'adhésion {$adhesion->id}: " . $e->getMessage());
            }
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("   ✅ {$processed} calcul(s) d'intérêts " . ($isDryRun ? 'simulé(s)' : 'programmé(s)'));

        return $processed;
    }

    /**
     * Envoyer les rappels de paiement
     */
    private function sendPaymentReminders(bool $isDryRun): int
    {
        // Désactivé avec la simplification des crédits
        return 0;
    }

    /**
     * Détecter les paiements en retard
     */
    private function detectOverduePayments(bool $isDryRun): int
    {
        // Désactivé avec la simplification des crédits
        return 0;
    }

    /**
     * Effectuer les tâches de nettoyage
     */
    private function performCleanupTasks(bool $isDryRun): int
    {
        $this->info('🧹 Nettoyage et optimisation...');

        $cleanupTasks = [
            'Notifications anciennes' => function() use ($isDryRun) {
                $notificationService = app(NotificationService::class);
                return $isDryRun ? 0 : $notificationService->cleanOldNotifications(30);
            },
            'Cache expiré' => function() use ($isDryRun) {
                if ($isDryRun) return 0;
                
                $performanceService = app(PerformanceOptimizationService::class);
                $performanceService->clearExpiredCache();
                return 1; // Symbolique
            },
            'Logs anciens' => function() use ($isDryRun) {
                if ($isDryRun) return 0;
                
                // Nettoyer les logs de connexion de plus de 90 jours
                return \App\Models\LogConnexion::where('created_at', '<', now()->subDays(90))
                    ->delete();
            }
        ];

        $totalCleaned = 0;
        foreach ($cleanupTasks as $taskName => $task) {
            try {
                $cleaned = $task();
                $this->info("   🧹 {$taskName}: {$cleaned} entrée(s) " . ($isDryRun ? 'à nettoyer' : 'nettoyée(s)'));
                $totalCleaned += $cleaned;
            } catch (\Exception $e) {
                $this->error("   ❌ Erreur lors du nettoyage de {$taskName}: " . $e->getMessage());
            }
        }

        return $totalCleaned;
    }

    /**
     * Afficher les résultats de la maintenance
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info('📋 Résumé de la maintenance quotidienne:');
        $this->line('   💰 Intérêts calculés: ' . $results['interests_calculated']);
        $this->line('   📧 Rappels envoyés: ' . $results['reminders_sent']);
        $this->line('   ⚠️  Impayés détectés: ' . $results['overdue_detected']);
        $this->line('   🧹 Entrées nettoyées: ' . $results['cache_entries_cleaned']);
        
        if (!empty($results['errors'])) {
            $this->newLine();
            $this->warn('⚠️  Erreurs rencontrées:');
            foreach ($results['errors'] as $error) {
                $this->error('   - ' . $error);
            }
        }
    }
}