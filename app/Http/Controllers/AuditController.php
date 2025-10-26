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
        $q = Audit::with('user');

        // Filtrage par utilisateur
        if ($request->filled('user_id')) {
            $q->where('user_id', $request->integer('user_id'));
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

        // Récupération des données pour les filtres
        $users = \App\Models\User::whereHas('audits')->orderBy('name')->get();
        $modelTypes = Audit::select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        // Récupération des audits avec pagination
        $audits = $q->latest('created_at')->paginate(20);

        return view('backoffice.audit.index', [
            'audits' => $audits,
            'users' => $users,
            'modelTypes' => $modelTypes
        ]);
    }

    // Autres méthodes du contrôleur...
}