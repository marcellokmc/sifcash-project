<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\FilterAdherentsByAgent;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'log.user.activity' => \App\Http\Middleware\LogUserActivity::class,
            'audit' => \App\Http\Middleware\AuditActions::class,
            'filter.adherents.by.agent' => \App\Http\Middleware\FilterAdherentsByAgent::class
        ]);
        
        // Apply audit middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\AuditActions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        App\Providers\HorizonServiceProvider::class,
    ])->create();
