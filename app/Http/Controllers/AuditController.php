<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = Audit::with(['user', 'targetUser']);

        // Périmètre Chef de service: restreindre aux utilisateurs de son agence
        $auth = $request->user();
        if ($auth && $auth->isChefService()) {
            $q->where(function($sub) use ($auth) {
                $sub->whereHas('user', function($u) use ($auth) {
                        $u->where('agence_id', $auth->agence_id);
                    })
                    ->orWhereHas('targetUser', function($tu) use ($auth) {
                        $tu->where('agence_id', $auth->agence_id);
                    });
            });
        }

        // Filtrage par utilisateur (qui a fait l'action)
        if ($request->filled('user_id')) {
            $q->where('user_id', $request->integer('user_id'));
        }
        
        // Filtrage par utilisateur cible (sur qui l'action a été faite)
        if ($request->filled('target_user_id')) {
            $q->where('target_user_id', $request->integer('target_user_id'));
        }
        
        // Filtrage par catégorie d'action
        if ($request->filled('action_category')) {
            $q->where('action_category', $request->query('action_category'));
        }
        
        // Filtrage par type d'action
        if ($request->filled('action')) {
            $q->where('action', $request->query('action'));
        }
        
        // Filtrage par type de modèle
        if ($request->filled('model_type')) {
            $q->where('model_type', 'LIKE', '%' . $request->query('model_type'));
        }
        
        // Filtrage par date
        if ($request->filled('date_from')) {
            $q->where('created_at', '>=', $request->date('date_from') . ' 00:00:00');
        }
        if ($request->filled('date_to')) {
            $q->where('created_at', '<=', $request->date('date_to') . ' 23:59:59');
        }

        // Recherche dans la description
        if ($request->filled('search')) {
            $q->where('description', 'LIKE', '%' . $request->query('search') . '%');
        }

        // Récupération des données pour les filtres
        $users = \App\Models\User::whereHas('audits')->orderBy('name')->get();
        $targetUsers = \App\Models\User::whereHas('targetedAudits')->orderBy('name')->get();
        $modelTypes = Audit::select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');
        $categories = Audit::select('action_category')
            ->distinct()
            ->whereNotNull('action_category')
            ->orderBy('action_category')
            ->pluck('action_category');

        // Récupération des audits avec pagination
        $audits = $q->latest('created_at')->paginate(20);

        // API JSON si demandé explicitement
        if ($request->query('format') === 'json' || $request->wantsJson()) {
            return response()->json($audits);
        }

        return view('backoffice.audit.index', [
            'audits' => $audits,
            'users' => $users,
            'targetUsers' => $targetUsers,
            'modelTypes' => $modelTypes,
            'categories' => $categories
        ]);
    }

    /**
     * Obtenir les statistiques d'audit
     */
    public function stats(Request $request)
    {
        $stats = [
            'total' => Audit::count(),
            'today' => Audit::whereDate('created_at', today())->count(),
            'this_week' => Audit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Audit::whereMonth('created_at', now()->month)->count(),
            
            'by_category' => Audit::selectRaw('action_category, COUNT(*) as count')
                ->groupBy('action_category')
                ->pluck('count', 'action_category'),
            
            'by_action' => Audit::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'action'),
            
            'top_users' => Audit::selectRaw('user_id, COUNT(*) as count')
                ->with('user:id,name')
                ->groupBy('user_id')
                ->orderByDesc('count')
                ->limit(10)
                ->get()
                ->mapWithKeys(fn($item) => [$item->user->name ?? 'Système' => $item->count]),
        ];

        if ($request->wantsJson()) {
            return response()->json($stats);
        }

        return view('backoffice.audit.stats', compact('stats'));
    }

    /**
     * Afficher les détails d'un audit spécifique
     */
    public function show(Audit $audit)
    {
        $audit->load(['user', 'targetUser', 'model']);
        
        return view('backoffice.audit.show', compact('audit'));
    }

    /**
     * Exporter les audits en CSV
     */
    public function export(Request $request)
    {
        $q = Audit::with(['user', 'targetUser']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('user_id')) {
            $q->where('user_id', $request->integer('user_id'));
        }
        if ($request->filled('action_category')) {
            $q->where('action_category', $request->query('action_category'));
        }
        if ($request->filled('date_from')) {
            $q->where('created_at', '>=', $request->date('date_from') . ' 00:00:00');
        }
        if ($request->filled('date_to')) {
            $q->where('created_at', '<=', $request->date('date_to') . ' 23:59:59');
        }

        $audits = $q->latest('created_at')->get();

        $filename = 'audits_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($audits) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Utilisateur', 'Action', 'Catégorie', 'Modèle', 'ID', 'Utilisateur Cible', 'Description', 'IP']);

            foreach ($audits as $audit) {
                fputcsv($file, [
                    $audit->created_at->format('Y-m-d H:i:s'),
                    $audit->user->name ?? 'Système',
                    $audit->action,
                    $audit->action_category,
                    class_basename($audit->model_type),
                    $audit->model_id,
                    $audit->targetUser->name ?? '',
                    $audit->description,
                    $audit->ip,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
