<?php

namespace App\Http\Controllers;

use App\Models\LogConnexion;
use Illuminate\Http\Request;

class LogConnexionController extends Controller
{
    public function index(Request $request)
{
    $query = LogConnexion::with(['user' => function($q) {
        $q->select('id', 'name', 'email');
    }])->latest();

    // Filtrage par utilisateur
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // Filtrage par type d'action
    if ($request->filled('action') && in_array($request->action, ['login', 'logout', 'failed_login'])) {
        $query->where('action', $request->action);
    }

    // Recherche textuelle
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhere('ip_address', 'like', "%{$search}%")
            ->orWhere('user_agent', 'like', "%{$search}%");
        });
    }

    // Filtrage par date
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $logs = $query->paginate(10);
    
    // Si c'est une requête AJAX, on retourne du JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json($logs);
    }
    
    // Statistiques pour la vue
    $stats = [
        'successful_logins' => LogConnexion::where('action', 'login')->count(),
        'failed_logins' => LogConnexion::where('action', 'failed_login')->count(),
        'logouts' => LogConnexion::where('action', 'logout')->count(),
        'active_users' => LogConnexion::distinct('user_id')->where('action', 'login')
                                    ->whereDate('created_at', '>=', now()->subDays(30))
                                    ->count(),
        'today_logins' => LogConnexion::where('action', 'login')
                                    ->whereDate('created_at', today())
                                    ->count(),
    ];
    
    // Sinon, on retourne la vue
    $users = \App\Models\User::whereHas('logsConnexion')->orderBy('name')->get(['id', 'name']);
    
    return view('backoffice.logs.connexions', [
        'logs' => $logs,
        'users' => $users,
        'filters' => $request->only(['user_id', 'action', 'date_from', 'date_to', 'search']),
        'stats' => $stats
    ]);
}

    public function export(Request $request)
    {
        $query = LogConnexion::with(['user' => function($q) {
            $q->select('id', 'name', 'email');
        }])->latest();

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action') && in_array($request->action, ['login', 'logout', 'failed_login'])) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('ip_address', 'like', "%{$search}%")
                ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(1000)->get(); // Limite à 1000 pour la performance

        $filename = 'logs_connexions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers CSV
            fputcsv($file, ['Date/Heure', 'Utilisateur', 'Email', 'Action', 'Adresse IP', 'Navigateur']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user ? $log->user->name : 'Utilisateur supprimé',
                    $log->user ? $log->user->email : 'N/A',
                    ucfirst($log->action),
                    $log->ip_address,
                    $log->user_agent
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}