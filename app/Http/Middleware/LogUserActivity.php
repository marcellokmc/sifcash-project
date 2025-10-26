<?php

namespace App\Http\Middleware;

use App\Models\LogConnexion;
use Closure;
use Illuminate\Http\Request;

class LogUserActivity
{
        public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // On ne logue que les requêtes authentifiées
        if (auth()->check()) {
            $action = $request->is('logout') ? 'logout' : 'login';
            
            LogConnexion::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => $action,
                'created_at' => now()
            ]);
        }

        return $response;
    }
}