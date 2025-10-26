<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class FilterAdherentsByAgent
{
    /**
     * Handle an incoming request.
     *
     * Filtre les adhérents visibles par un agent pour ne montrer que ceux qui lui sont affectés
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si l'utilisateur est un agent ou chef de service, on applique le filtre
        if ($user && in_array($user->role, ['agent', 'chef_service'])) {
            // Ajouter l'ID de l'agent dans la requête pour utilisation dans les contrôleurs
            $request->merge(['agent_filter' => $user->id]);
        }

        return $next($request);
    }
}
