<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adherent;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Throwable;

class SendBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdays:send-notifications {--dry-run : Affiche ce qui serait envoyé sans créer de notifications} {--limit=0 : Limite le nombre de notifications envoyées}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Envoyer des notifications de joyeux anniversaire aux adhérents (prévention des doublons, email optionnel)";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎉 Recherche des anniversaires du jour...');

        $today = Carbon::today();
        $dryRun = (bool) $this->option('dry-run');
        $limit = (int) $this->option('limit');

        // Sélectionne les adhérents dont c'est l'anniversaire aujourd'hui
        $query = Adherent::with('user')
            ->whereNotNull('date_naissance')
            ->where('statut_compte', 'actif');

        // Utilise les colonnes générées si disponibles pour profiter des index
        if (Schema::hasColumn('adherents', 'birth_month') && Schema::hasColumn('adherents', 'birth_day')) {
            $query->where(function ($q) use ($today) {
                $q->where('birth_month', $today->month)
                  ->where('birth_day', $today->day);
                // Cas 29/02 traité le 28/02 en année non bissextile
                if (!$today->isLeapYear() && $today->month === 2 && $today->day === 28) {
                    $q->orWhere(function ($qq) {
                        $qq->where('birth_month', 2)->where('birth_day', 29);
                    });
                }
            });
        } else {
            // Fallback sans colonnes générées
            $query->where(function ($q) use ($today) {
                $q->whereMonth('date_naissance', $today->month)
                  ->whereDay('date_naissance', $today->day);
                if (!$today->isLeapYear() && $today->month === 2 && $today->day === 28) {
                    $q->orWhere(function ($qq) {
                        $qq->whereMonth('date_naissance', 2)->whereDay('date_naissance', 29);
                    });
                }
            });
        }

        $query->orderBy('id');

        $count = 0;
        $processed = 0;

        $query->chunkById(200, function ($adherents) use (&$count, &$processed, $today, $dryRun, $limit) {
            foreach ($adherents as $adherent) {
                if ($limit && $processed >= $limit) {
                    return false; // stop chunking
                }
                $processed++;

                if (!$adherent->user) {
                    continue;
                }

                // Calcul de l'âge
                $age = $today->diffInYears($adherent->date_naissance);

                // Déduplication: une seule notification anniversaire par jour et par user
                $alreadySent = Notification::where('user_id', $adherent->user->id)
                    ->where('type', 'anniversaire')
                    ->whereDate('created_at', $today)
                    ->exists();
                if ($alreadySent) {
                    $this->warn("⚠️  Déjà envoyé: {$adherent->nom_complet}");
                    continue;
                }

                $title = '🎂 Joyeux Anniversaire !';
                $message = "Chèr(e) {$adherent->prenom},\n\n" .
                    "🎉 Toute l'équipe de SIFCash-Burkina vous souhaite un très joyeux anniversaire !\n\n" .
                    "🎂 Vous célébrez aujourd'hui vos {$age} ans. Que cette nouvelle année vous apporte santé, bonheur et prospérité !\n\n" .
                    "Merci de votre confiance et de votre fidélité.\n\n" .
                    "Bien à vous,\nL'équipe SIFCash-Burkina 🎁";

                if ($dryRun) {
                    $this->line("[DRY-RUN] {$adherent->nom_complet} ({$age} ans) – notification + email=" . (config('birthday.email') ? 'on' : 'off'));
                    $count++;
                    continue;
                }

                try {
                    // Notification in‑app
                    NotificationService::creerNotification(
                        $adherent->user->id,
                        $title,
                        $message,
                        'anniversaire'
                    );

                    // Email optionnel
                    if (config('birthday.email') && filter_var($adherent->email, FILTER_VALIDATE_EMAIL)) {
Mail::to($adherent->email)->queue(new \App\Mail\AdherentBirthdayMail($adherent, $age));
                    }

                    $count++;
                    $this->info("✅ Envoyé à {$adherent->nom_complet} ({$age} ans)");
                } catch (Throwable $e) {
                    $this->error("❌ Échec pour {$adherent->nom_complet}: " . $e->getMessage());
                }
            }
        });

        $this->info("");
        $this->info("✨ {$count} notification(s) d'anniversaire traitée(s) avec succès !");

        return 0;
    }
}
