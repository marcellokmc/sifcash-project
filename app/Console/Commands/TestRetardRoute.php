<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Credit;
use App\Models\EcheanceCredit;
use App\Http\Controllers\CreditController;
use Illuminate\Http\Request;

class TestRetardRoute extends Command
{
    protected $signature = 'test:retard';
    protected $description = 'Test la route credits en retard';

    public function handle()
    {
        $this->info('Test de la route credits en retard...');
        
        try {
            // Test direct de la requête
            $credits = Credit::whereHas('echeances', function($query) {
                    $query->where('date_echeance', '<', now())
                          ->where('statut', '!=', 'paye');
                })
                ->with(['adherent', 'echeances' => function($query) {
                    $query->where('date_echeance', '<', now())
                          ->where('statut', '!=', 'paye')
                          ->orderBy('date_echeance');
                }])
                ->latest()
                ->get();
                
            $this->info('Crédits en retard trouvés: ' . $credits->count());
            
            foreach($credits as $credit) {
                $this->line('Crédit ID: ' . $credit->id . ' - Adhérent: ' . $credit->adherent->nom_complet);
            }
            
            // Test du contrôleur
            $this->info('\nTest du contrôleur...');
            $controller = new CreditController(app(\App\Services\CreditScheduleService::class));
            $request = Request::create('/admin/credits/en-retard');
            $response = $controller->creditsEnRetard($request);
            
            $this->info('Réponse du contrôleur reçue avec succès');
            
        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
