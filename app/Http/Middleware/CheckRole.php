<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next, ...$roles)
 {
    $user = $request->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Vérifier si l'utilisateur a l'un des rôles requis
    if (!in_array($user->role, $roles)) {
        abort(403, 'Accès non autorisé. Rôle requis: ' . implode(', ', $roles));
    }

    return $next($request);
}
}