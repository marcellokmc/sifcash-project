<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CalculateInterestsJob;
use App\Jobs\SendPaymentRemindersJob;
use App\Models\Adhesion;
use App\Models\EcheanceCredit;
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

            // 2. Envoi des rappels de paiement
            if (!$onlyInterests && !$onlyCleanup) {
                $results['reminders_sent'] = $this->sendPaymentReminders($isDryRun);
            }

            // 3. Détection des impayés
            if (!$onlyInterests && !$onlyReminders && !$onlyCleanup) {
                $results['overdue_detected'] = $this->detectOverduePayments($isDryRun);
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
        $this->info('📧 Envoi des rappels de paiement...');

        $reminderDays = [7, 3, 1]; // Rappels à 7, 3 et 1 jour avant échéance
        $totalReminders = 0;

        foreach ($reminderDays as $days) {
            $targetDate = now()->addDays($days)->toDateString();
            
            $upcomingEcheances = EcheanceCredit::with(['credit.adherent'])
                ->where('date_echeance', $targetDate)
                ->where('statut', 'en_attente')
                ->whereDoesntHave('credit.adherent.user', function($query) {
                    $query->where('active', false);
                })
                ->count();

            if ($upcomingEcheances > 0) {
                $this->info("   📅 {$upcomingEcheances} échéance(s) due(s) dans {$days} jour(s)");
                
                if (!$isDryRun) {
                    SendPaymentRemindersJob::dispatch($days, now()->toDateString())
                        ->onQueue('notifications');
                }
                
                $totalReminders += $upcomingEcheances;
            }
        }

        $this->info("   ✅ {$totalReminders} rappel(s) " . ($isDryRun ? 'simulé(s)' : 'programmé(s)'));
        return $totalReminders;
    }

    /**
     * Détecter les paiements en retard
     */
    private function detectOverduePayments(bool $isDryRun): int
    {
        $this->info('⚠️  Détection des paiements en retard...');

        $overdueEcheances = EcheanceCredit::with(['credit.adherent.user'])
            ->where('date_echeance', '<', now()->toDateString())
            ->where('statut', 'en_attente')
            ->get();

        if ($overdueEcheances->isEmpty()) {
            $this->info('   ✅ Aucun paiement en retard détecté');
            return 0;
        }

        $this->warn("   ⚠️  {$overdueEcheances->count()} paiement(s) en retard détecté(s)");

        // Regrouper par nombre de jours de retard
        $groupedOverdue = $overdueEcheances->groupBy(function($echeance) {
            return now()->diffInDays($echeance->date_echeance);
        });

        foreach ($groupedOverdue as $daysOverdue => $echeances) {
            $this->warn("     - {$echeances->count()} paiement(s) en retard de {$daysOverdue} jour(s)");
        }

        // Notifier les administrateurs si retards significatifs
        $criticalOverdue = $overdueEcheances->filter(function($echeance) {
            return now()->diffInDays($echeance->date_echeance) > 7; // Plus de 7 jours
        });

        if (!$isDryRun && $criticalOverdue->count() > 0) {
            $notificationService = app(NotificationService::class);
            $notificationService->notifyOverduePayments($criticalOverdue->toArray());
        }

        return $overdueEcheances->count();
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