<?php

namespace App\Jobs;

use App\Models\EcheanceCredit;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendPaymentRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;

    protected $daysBeforeDue;
    protected $batchDate;

    public function __construct(int $daysBeforeDue = 3, ?string $batchDate = null)
    {
        $this->daysBeforeDue = $daysBeforeDue;
        $this->batchDate = $batchDate ?? now()->toDateString();
        
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            $reminderDate = Carbon::parse($this->batchDate)->addDays($this->daysBeforeDue);
            
            // Récupérer les échéances dues dans X jours
            $upcomingEcheances = EcheanceCredit::with(['credit.adherent.user', 'credit.plan'])
                ->where('date_echeance', $reminderDate->toDateString())
                ->where('statut', 'en_attente')
                ->get();

            $remindersSent = 0;
            $errors = 0;

            foreach ($upcomingEcheances as $echeance) {
                try {
                    $adherentUser = $echeance->credit->adherent->user;
                    
                    // Vérifier que l'utilisateur est actif
                    if (!$adherentUser->active) {
                        continue;
                    }

                    $message = $this->buildReminderMessage($echeance);
                    
                    $notificationService->notifyPaymentDue(
                        $adherentUser,
                        $echeance->montant_attendu,
                        $echeance->date_echeance->format('d/m/Y')
                    );

                    // Optionnel: marquer l'échéance comme ayant reçu un rappel
                    $echeance->update([
                        'reminder_sent_at' => now(),
                        'reminder_count' => ($echeance->reminder_count ?? 0) + 1
                    ]);

                    $remindersSent++;
                    
                    // Petit délai pour éviter la surcharge
                    usleep(100000); // 0.1 seconde

                } catch (\Exception $e) {
                    Log::warning('Failed to send payment reminder', [
                        'echeance_id' => $echeance->id,
                        'adherent_id' => $echeance->credit->adherent_id,
                        'error' => $e->getMessage()
                    ]);
                    $errors++;
                }
            }

            Log::info('Payment reminders batch completed', [
                'batch_date' => $this->batchDate,
                'days_before_due' => $this->daysBeforeDue,
                'reminder_date' => $reminderDate->toDateString(),
                'total_found' => $upcomingEcheances->count(),
                'reminders_sent' => $remindersSent,
                'errors' => $errors
            ]);

            // Notifier les admins du résumé si des erreurs
            if ($errors > 0) {
                $this->notifyAdminsOfErrors($errors, $remindersSent, $notificationService);
            }

        } catch (\Exception $e) {
            Log::error('Payment reminders job failed', [
                'batch_date' => $this->batchDate,
                'days_before_due' => $this->daysBeforeDue,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Construire le message de rappel personnalisé
     */
    private function buildReminderMessage(EcheanceCredit $echeance): string
    {
        $adherent = $echeance->credit->adherent;
        $montant = number_format($echeance->montant_attendu, 0, ',', ' ');
        $dateEcheance = $echeance->date_echeance->format('d/m/Y');
        
        $daysUntilDue = now()->diffInDays($echeance->date_echeance, false);
        
        if ($daysUntilDue > 1) {
            $timePhrase = "dans {$daysUntilDue} jours";
        } elseif ($daysUntilDue === 1) {
            $timePhrase = "demain";
        } else {
            $timePhrase = "aujourd'hui";
        }

        return "Bonjour {$adherent->prenom}, votre échéance de crédit de {$montant} FCFA est due {$timePhrase} ({$dateEcheance}). Pensez à effectuer votre paiement pour éviter les pénalités.";
    }

    /**
     * Notifier les admins des erreurs
     */
    private function notifyAdminsOfErrors(int $errors, int $sent, NotificationService $notificationService): void
    {
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
            $notificationService->sendSystemNotification(
                $admin,
                'payment_reminder_errors',
                'Erreurs lors de l\'envoi des rappels',
                "Batch des rappels de paiement terminé avec {$errors} erreur(s). {$sent} rappels envoyés avec succès.",
                [
                    'batch_date' => $this->batchDate,
                    'errors' => $errors,
                    'sent' => $sent,
                    'days_before_due' => $this->daysBeforeDue
                ]
            );
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Payment reminders job failed permanently', [
            'batch_date' => $this->batchDate,
            'days_before_due' => $this->daysBeforeDue,
            'exception' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}