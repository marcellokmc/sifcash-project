<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!class_exists(\Laravel\Horizon\Horizon::class)) {
            return; // Horizon non installé en environnement courant
        }

        \Laravel\Horizon\Horizon::auth(function ($request) {
            $user = $request->user();
            return $user && in_array($user->role, ['admin', 'chef_service']);
        });
    }
}
