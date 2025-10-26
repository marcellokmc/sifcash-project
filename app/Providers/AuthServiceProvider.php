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
        \App\Models\EcheanceCredit::class => \App\Policies\EcheanceCreditPolicy::class,
        \App\Models\ConditionEligibiliteCredit::class => \App\Policies\ConditionEligibiliteCreditPolicy::class,
        \App\Models\Paiement::class => \App\Policies\PaiementPolicy::class,
        \App\Models\DemandeRetrait::class => \App\Policies\DemandeRetraitPolicy::class,
        \App\Models\PaiementCredit::class => \App\Policies\PaiementCreditPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Grant all abilities to admin users
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return method_exists($user, 'isAdmin') && $user->isAdmin() ? true : null;
        });
    }
}
