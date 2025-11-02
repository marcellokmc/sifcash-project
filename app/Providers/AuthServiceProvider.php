<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\AyantDroit::class => \App\Policies\AyantDroitPolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
        \App\Models\Adhesion::class => \App\Policies\AdhesionPolicy::class,
        \App\Models\Plan::class => \App\Policies\PlanPolicy::class,
        \App\Models\Credit::class => \App\Policies\CreditPolicy::class,
        \App\Models\ConditionEligibiliteCredit::class => \App\Policies\ConditionEligibiliteCreditPolicy::class,
        \App\Models\Paiement::class => \App\Policies\PaiementPolicy::class,
        \App\Models\DemandeRetrait::class => \App\Policies\DemandeRetraitPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Grant all abilities to admin users
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return method_exists($user, 'isAdmin') && $user->isAdmin() ? true : null;
        });

        // Définir les gates pour toutes les permissions
        $this->registerPermissionGates();
    }

    /**
     * Enregistrer les gates pour toutes les permissions
     */
    protected function registerPermissionGates()
    {
        try {
            $permissions = \App\Models\Permission::all();
            
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });
            }
        } catch (\Exception $e) {
            // Si la table permissions n'existe pas encore (migrations), on ignore
            report($e);
        }
    }
}
