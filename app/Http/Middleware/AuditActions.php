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
        $old = null;

        // Try to infer model from route parameters (e.g., {credit}, {adherent}, {user})
        $routeParams = $route?->parameters() ?? [];
        foreach ($routeParams as $paramName => $param) {
            if (is_object($param) && method_exists($param, 'getTable')) {
                $modelType = get_class($param);
                $modelId = $param->id ?? $param->getKey() ?? null;
                // Snapshot of old data if it's an Eloquent model
                $old = method_exists($param, 'toArray') ? $param->toArray() : null;
                break;
            }
        }

        // If no model in route params, try to infer from request data and route name
        if (!$modelType) {
            $modelType = $this->inferModelFromRoute($actionName, $request);
            $modelId = $this->inferModelIdFromRequest($request);
        }

        $response = $next($request);

        try {
            // Only log if user is authenticated or if it's a significant action
            if ($user || in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                Audit::create([
                    'user_id' => $user?->id,
                    'action' => (string) $actionName,
                    'model_type' => $modelType ?? 'n/a',
                    'model_id' => $modelId ?? 0,
                    'ancienne_valeur' => $old,
                    'nouvelle_valeur' => [
                        'status' => $response->getStatusCode(),
                        'method' => $request->method(),
                        'request' => $request->except(['password','password_confirmation','_token','_method']),
                    ],
                    'ip' => (string) $request->ip(),
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // best-effort; do not block request
            \Log::error('Audit logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Infer model type from route name
     */
    private function inferModelFromRoute(string $routeName, Request $request): ?string
    {
        // Map route patterns to models
        $patterns = [
            'credit' => \App\Models\Credit::class,
            'adherent' => \App\Models\Adherent::class,
            'user' => \App\Models\User::class,
            'affectation' => \App\Models\Affectation::class,
            'epargne' => \App\Models\Epargne::class,
            'transaction' => \App\Models\Transaction::class,
            'compte' => \App\Models\Compte::class,
            'notification' => \App\Models\Notification::class,
        ];

        foreach ($patterns as $keyword => $modelClass) {
            if (Str::contains($routeName, $keyword)) {
                return $modelClass;
            }
        }

        return null;
    }

    /**
     * Infer model ID from request data
     */
    private function inferModelIdFromRequest(Request $request): ?int
    {
        // Try common ID field names
        $idFields = ['id', 'credit_id', 'adherent_id', 'user_id', 'affectation_id'];
        
        foreach ($idFields as $field) {
            if ($request->has($field)) {
                return (int) $request->input($field);
            }
        }

        return null;
    }
}
