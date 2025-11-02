<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\ModelChangeObserver;
use App\Models\Adherent;
use App\Models\Adhesion;
use App\Models\Credit;
use App\Models\Epargne;
use App\Models\Document;
use App\Models\DemandeRetrait;
use App\Models\Paiement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Schema::defaultStringLength(191);

         // Invalidation ciblée du cache via un observer générique
         Adherent::observe(ModelChangeObserver::class);
         Adhesion::observe(ModelChangeObserver::class);
         Credit::observe(ModelChangeObserver::class);
         Epargne::observe(ModelChangeObserver::class);
         Document::observe(ModelChangeObserver::class);
         DemandeRetrait::observe(ModelChangeObserver::class);
         Paiement::observe(ModelChangeObserver::class);
    }
}
