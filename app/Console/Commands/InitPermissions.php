<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:init {--force : Forcer la réinitialisation même si les permissions existent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialiser le système de permissions et rôles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Initialisation du système de permissions...');
        $this->newLine();

        // Vérifier si on doit forcer
        $force = $this->option('force');

        if (!$force) {
            $permissionCount = \App\Models\Permission::count();
            if ($permissionCount > 0) {
                $this->warn("⚠️  Des permissions existent déjà ({$permissionCount} permissions trouvées).");
                if (!$this->confirm('Voulez-vous continuer et écraser les permissions existantes ?', false)) {
                    $this->info('❌ Opération annulée.');
                    return 0;
                }
            }
        }

        // Étape 1 : Seeders
        $this->info('📝 Étape 1/4 : Exécution des seeders...');
        $this->call('db:seed', ['--class' => 'PermissionSeeder']);
        $this->call('db:seed', ['--class' => 'RoleSeeder']);
        $this->call('db:seed', ['--class' => 'RolePermissionSeeder']);
        $this->info('✓ Seeders exécutés avec succès');
        $this->newLine();

        // Étape 2 : Cache
        $this->info('🧹 Étape 2/4 : Nettoyage des caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $this->info('✓ Caches nettoyés');
        $this->newLine();

        // Étape 3 : Optimisation
        $this->info('⚡ Étape 3/4 : Optimisation de l\'application...');
        Artisan::call('optimize');
        $this->info('✓ Application optimisée');
        $this->newLine();

        // Étape 4 : Statistiques
        $this->info('📊 Étape 4/4 : Statistiques du système...');
        $this->displayStatistics();
        $this->newLine();

        $this->info('✅ Initialisation terminée avec succès !');
        $this->newLine();
        
        $this->comment('💡 Conseil : Utilisez "php artisan permissions:check {email}" pour vérifier les permissions d\'un utilisateur');
        
        return 0;
    }

    /**
     * Afficher les statistiques du système
     */
    protected function displayStatistics()
    {
        $permissions = \App\Models\Permission::count();
        $roles = \App\Models\Role::count();
        
        $this->table(
            ['Rôle', 'Nombre de Permissions'],
            $this->getRolePermissionsCounts()
        );

        $this->info("Total : {$permissions} permissions | {$roles} rôles");
    }

    /**
     * Obtenir le nombre de permissions par rôle
     */
    protected function getRolePermissionsCounts()
    {
        return \App\Models\Role::with('permissions')->get()->map(function ($role) {
            return [
                'rôle' => ucfirst(str_replace('_', ' ', $role->name)),
                'permissions' => $role->permissions->count()
            ];
        })->toArray();
    }
}
