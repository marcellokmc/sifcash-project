<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adherent;
use App\Models\Notification;
use Carbon\Carbon;

class SendBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdays:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des notifications de joyeux anniversaire aux adhérents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎉 Recherche des anniversaires du jour...');
        
        $today = Carbon::today();
        
        // Récupérer tous les adhérents dont c'est l'anniversaire aujourd'hui
        $adherents = Adherent::with('user')
            ->whereMonth('date_naissance', $today->month)
            ->whereDay('date_naissance', $today->day)
            ->where('statut_compte', 'actif')
            ->get();
        
        if ($adherents->isEmpty()) {
            $this->info('ℹ️ Aucun anniversaire aujourd\'hui.');
            return 0;
        }
        
        $count = 0;
        
        foreach ($adherents as $adherent) {
            if (!$adherent->user) {
                continue;
            }
            
            // Calculer l'âge
            $age = $today->diffInYears($adherent->date_naissance);
            
            // Vérifier si une notification d'anniversaire a déjà été envoyée aujourd'hui
            $alreadySent = Notification::where('user_id', $adherent->user->id)
                ->where('type', 'anniversaire')
                ->whereDate('created_at', $today)
                ->exists();
            
            if ($alreadySent) {
                $this->warn("⚠️  Notification déjà envoyée pour {$adherent->nom_complet}");
                continue;
            }
            
            // Créer la notification d'anniversaire
            Notification::create([
                'user_id' => $adherent->user->id,
                'titre' => '🎂 Joyeux Anniversaire !',
                'message' => "Chèr(e) {$adherent->prenom},\n\n🎉 Toute l'équipe de SIFCash-Burkina vous souhaite un très joyeux anniversaire ! \n\n🎂 Vous célébrez aujourd'hui vos {$age} ans. Que cette nouvelle année vous apporte santé, bonheur et prospérité !\n\nMerci de votre confiance et de votre fidélité.\n\nBien à vous,\nL'équipe SIFCash-Burkina 🎁",
                'type' => 'anniversaire',
                'lu' => false,
            ]);
            
            $count++;
            $this->info("✅ Notification envoyée à {$adherent->nom_complet} ({$age} ans)");
        }
        
        $this->info("");
        $this->info("✨ {$count} notification(s) d'anniversaire envoyée(s) avec succès !");
        
        return 0;
    }
}
