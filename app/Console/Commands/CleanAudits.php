<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Audit;
use Illuminate\Support\Str;

class CleanAudits extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'audit:clean {--all : Nettoyer tous les audits non pertinents}';

    /**
     * The console command description.
     */
    protected $description = 'Nettoyer les audits non pertinents (notifications API, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage des audits non pertinents...');

        // Patterns à supprimer
        $patternsToDelete = [
            'unreadCount',
            'api/notifications',
            'heartbeat',
        ];

        $count = 0;

        foreach ($patternsToDelete as $pattern) {
            $deleted = Audit::where('action', 'LIKE', "%{$pattern}%")
                ->orWhere('description', 'LIKE', "%{$pattern}%")
                ->delete();
            
            $count += $deleted;
            $this->line("Supprimé {$deleted} audits contenant '{$pattern}'");
        }

        // Supprimer les audits avec model_type = 'n/a' et model_id = 0
        $deletedNA = Audit::where('model_type', 'n/a')
            ->where('model_id', 0)
            ->where('action', 'NOT LIKE', '%show%')
            ->where('action', 'NOT LIKE', '%validate%')
            ->where('action', 'NOT LIKE', '%approve%')
            ->where('action', 'NOT LIKE', '%reject%')
            ->delete();
        
        $count += $deletedNA;
        $this->line("Supprimé {$deletedNA} audits génériques (n/a)");

        $this->info("Total: {$count} audits supprimés");
        $this->info('Nettoyage terminé !');

        return 0;
    }
}
