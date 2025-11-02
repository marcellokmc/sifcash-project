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
            // Exclure certaines routes peu importantes
            $excludedPatterns = [
                'api/notifications',
                'notifications/unread',
                'heartbeat',
                'health-check',
            ];
            
            $path = $request->path();
            foreach ($excludedPatterns as $pattern) {
                if (Str::contains($path, $pattern)) {
                    return $response;
                }
            }

            // Exclure les routes avec certains noms
            $excludedRouteNames = [
                'unreadCount',
                'heartbeat',
            ];
            
            foreach ($excludedRouteNames as $routeName) {
                if (Str::contains($actionName, $routeName)) {
                    return $response;
                }
            }

            // Ne pas auditer les requêtes GET de lecture simple (index, liste)
            if ($request->method() === 'GET' && !Str::contains($actionName, ['show', 'edit', 'create'])) {
                return $response;
            }

            // Only log if user is authenticated or if it's a significant action
            if ($user || in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Déterminer la catégorie et la description de l'action
                $category = $this->determineActionCategory($actionName, $request);
                $description = $this->generateActionDescription($actionName, $request, $response);
                $targetUserId = $this->determineTargetUserId($request);
                
                Audit::create([
                    'user_id' => $user?->id,
                    'action' => $this->normalizeAction($actionName, $request),
                    'action_category' => $category,
                    'model_type' => $modelType ?? 'n/a',
                    'model_id' => $modelId ?? 0,
                    'target_user_id' => $targetUserId,
                    'ancienne_valeur' => $old,
                    'nouvelle_valeur' => [
                        'status' => $response->getStatusCode(),
                        'method' => $request->method(),
                        'request' => $request->except(['password','password_confirmation','_token','_method']),
                    ],
                    'ip' => (string) $request->ip(),
                    'url' => (string) $request->fullUrl(),
                    'user_agent' => (string) $request->userAgent(),
                    'description' => $description,
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
            'paiement' => \App\Models\Paiement::class,
            'retrait' => \App\Models\DemandeRetrait::class,
            'adhesion' => \App\Models\Adhesion::class,
            'credit' => \App\Models\Credit::class,
            'adherent' => \App\Models\Adherent::class,
            'user' => \App\Models\User::class,
            'affectation' => \App\Models\Affectation::class,
            'epargne' => \App\Models\Epargne::class,
            'transaction' => \App\Models\Transaction::class,
            'compte' => \App\Models\Compte::class,
            'notification' => \App\Models\Notification::class,
            'plan' => \App\Models\Plan::class,
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
        $idFields = ['id', 'paiement_id', 'adhesion_id', 'retrait_id', 'credit_id', 'adherent_id', 'user_id', 'affectation_id'];
        
        foreach ($idFields as $field) {
            if ($request->has($field)) {
                return (int) $request->input($field);
            }
        }

        return null;
    }

    /**
     * Déterminer la catégorie d'action
     */
    private function determineActionCategory(string $routeName, Request $request): ?string
    {
        // Catégorisation basée sur les mots-clés dans le nom de route
        $categoryMap = [
            'paiement' => 'paiement',
            'retrait' => 'retrait',
            'adhesion' => 'adhesion',
            'affectation' => 'affectation',
            'adherent' => 'adherent',
            'user' => 'utilisateur',
            'plan' => 'plan',
            'credit' => 'credit',
            'validation' => 'validation',
            'approve' => 'validation',
            'reject' => 'validation',
        ];

        foreach ($categoryMap as $keyword => $category) {
            if (Str::contains(strtolower($routeName), $keyword)) {
                return $category;
            }
        }

        return 'autre';
    }

    /**
     * Normaliser le nom de l'action
     */
    private function normalizeAction(string $routeName, Request $request): string
    {
        // Actions spécifiques
        if (Str::contains($routeName, 'validate') || Str::contains($routeName, 'valider')) {
            return 'validated';
        }
        if (Str::contains($routeName, 'approve') || Str::contains($routeName, 'approuver')) {
            return 'approved';
        }
        if (Str::contains($routeName, 'reject') || Str::contains($routeName, 'rejeter')) {
            return 'rejected';
        }
        if (Str::contains($routeName, 'store') || $request->isMethod('POST')) {
            return 'created';
        }
        if (Str::contains($routeName, 'update') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            return 'updated';
        }
        if (Str::contains($routeName, 'destroy') || $request->isMethod('DELETE')) {
            return 'deleted';
        }

        return $routeName;
    }

    /**
     * Générer une description de l'action
     */
    private function generateActionDescription(string $routeName, Request $request, Response $response): string
    {
        $method = $request->method();
        $path = $request->path();
        $status = $response->getStatusCode();

        // Ne pas générer de description pour les actions en lecture seule ou API
        if ($method === 'GET' && !Str::contains($routeName, ['store', 'create', 'edit', 'show'])) {
            return null;
        }

        // Descriptions spécifiques selon le contexte
        if (Str::contains($routeName, 'paiement')) {
            if (Str::contains($routeName, 'validate')) {
                return "Validation d'un paiement";
            } elseif (Str::contains($routeName, 'reject')) {
                return "Rejet d'un paiement";
            } elseif (Str::contains($routeName, 'store')) {
                return "Soumission d'un nouveau paiement";
            } elseif (Str::contains($routeName, 'show')) {
                return "Consultation d'un paiement";
            }
        } elseif (Str::contains($routeName, 'retrait')) {
            if (Str::contains($routeName, 'approve') || Str::contains($routeName, 'validate')) {
                return "Approbation d'une demande de retrait";
            } elseif (Str::contains($routeName, 'reject')) {
                return "Rejet d'une demande de retrait";
            } elseif (Str::contains($routeName, 'process')) {
                return "Traitement d'une demande de retrait";
            } elseif (Str::contains($routeName, 'store')) {
                return "Soumission d'une demande de retrait";
            } elseif (Str::contains($routeName, 'show')) {
                return "Consultation d'une demande de retrait";
            }
        } elseif (Str::contains($routeName, 'adhesion')) {
            if (Str::contains($routeName, 'store')) {
                return "Création d'une nouvelle adhésion";
            } elseif (Str::contains($routeName, 'activate')) {
                return "Activation d'une adhésion";
            } elseif (Str::contains($routeName, 'close')) {
                return "Clôture d'une adhésion";
            } elseif (Str::contains($routeName, 'suspend')) {
                return "Suspension d'une adhésion";
            } elseif (Str::contains($routeName, 'resume')) {
                return "Réactivation d'une adhésion";
            } elseif (Str::contains($routeName, 'show')) {
                return "Consultation d'une adhésion";
            }
        } elseif (Str::contains($routeName, 'affectation')) {
            return "Affectation d'un agent à un adhérent";
        } elseif (Str::contains($routeName, 'adherent')) {
            if (Str::contains($routeName, 'store')) {
                return "Création d'un adhérent";
            } elseif (Str::contains($routeName, 'update')) {
                return "Modification d'un adhérent";
            } elseif (Str::contains($routeName, 'activate')) {
                return "Activation d'un adhérent";
            } elseif (Str::contains($routeName, 'deactivate')) {
                return "Désactivation d'un adhérent";
            } elseif (Str::contains($routeName, 'show')) {
                return "Consultation d'un adhérent";
            }
        } elseif (Str::contains($routeName, 'document')) {
            if (Str::contains($routeName, 'validate')) {
                return "Validation d'un document";
            } elseif (Str::contains($routeName, 'reject')) {
                return "Rejet d'un document";
            }
        } elseif (Str::contains($routeName, 'user')) {
            if (Str::contains($routeName, 'store')) {
                return "Création d'un utilisateur";
            } elseif (Str::contains($routeName, 'update')) {
                return "Modification d'un utilisateur";
            }
        }

        // Description par défaut basée sur la méthode HTTP
        $descriptions = [
            'POST' => "Création de ressource",
            'PUT' => "Modification de ressource",
            'PATCH' => "Modification partielle",
            'DELETE' => "Suppression de ressource",
        ];

        $description = $descriptions[$method] ?? null;

        // Ajouter le statut si c'est un échec
        if ($description && $status >= 400) {
            $description .= " (Échec)";
        }

        return $description;
    }

    /**
     * Déterminer l'ID de l'utilisateur ciblé par l'action
     */
    private function determineTargetUserId(Request $request): ?int
    {
        // Chercher un adherent_id dans la requête et récupérer le user_id correspondant
        if ($request->has('adherent_id')) {
            $adherent = \App\Models\Adherent::find($request->input('adherent_id'));
            return $adherent?->user_id;
        }

        // Chercher directement un user_id cible (différent de l'utilisateur connecté)
        if ($request->has('user_id') && $request->input('user_id') != $request->user()?->id) {
            return (int) $request->input('user_id');
        }

        return null;
    }
}
