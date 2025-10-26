<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Audit;
use Illuminate\Support\Str;

class AuditActions
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $route = $request->route();
        $actionName = $route?->getName() ?? ($route?->getActionName() ?? 'unknown');
        $modelType = null;
        $modelId = null;

        // Try to infer model from route parameters (e.g., {credit})
        $routeParams = $route?->parameters() ?? [];
        foreach ($routeParams as $param) {
            if (is_object($param) && method_exists($param, 'getTable')) {
                $modelType = get_class($param);
                $modelId = $param->id ?? null;
                break;
            }
        }

        $old = null;
        if ($modelType && $modelId) {
            // Snapshot of old data if it's an Eloquent model
            $old = method_exists($param, 'toArray') ? $param->toArray() : null;
        }

        $response = $next($request);

        try {
            Audit::create([
                'user_id' => $user?->id,
                'action' => (string) $actionName,
                'model_type' => $modelType ?? 'n/a',
                'model_id' => $modelId ?? 0,
                'ancienne_valeur' => $old,
                'nouvelle_valeur' => [
                    'status' => $response->getStatusCode(),
                    'request' => $request->except(['password','password_confirmation','_token']),
                ],
                'ip' => (string) $request->ip(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // best-effort; do not block request
        }

        return $response;
    }
}
