<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\User;
use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AffectationController extends Controller
{
    /**
     * Page principale d'affectation en masse
     */
    public function index(Request $request)
    {
        $auth = auth()->user();
        $query = Adherent::with(['agence', 'agents', 'agentGestionnaire'])
            ->withCount('agents');

        // Chef de service: restreindre à son agence
        if ($auth && $auth->isChefService()) {
            $query->where('agence_id', $auth->agence_id);
        }

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('membre_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('agence_id')) {
            $query->where('agence_id', $request->agence_id);
        }

        if ($request->filled('agent_id')) {
            $query->whereHas('agents', function($q) use ($request) {
                $q->where('agent_id', $request->agent_id);
            });
        }

        if ($request->filled('statut')) {
            $statut = $request->statut;
            if ($statut === 'non_affecte') {
                $query->nonAffectes();
            } elseif ($statut === 'affecte') {
                $query->has('agents');
            } elseif ($statut === 'sans_agence') {
                $query->whereNull('agence_id');
            }
        }

        $adherents = $query->latest()->paginate(50)->withQueryString();

        // Statistiques
        $stats = [
            'total' => Adherent::count(),
            'affectes' => Adherent::has('agents')->count(),
            'non_affectes' => Adherent::nonAffectes()->count(),
            'sans_agence' => Adherent::whereNull('agence_id')->count(),
        ];

        // Limiter la liste des agences/agents au périmètre
        if ($auth && $auth->isChefService()) {
            $agences = Agence::where('active', true)
                ->where('id', $auth->agence_id)
                ->orderBy('nom')
                ->get();
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->where('agence_id', $auth->agence_id)
                ->with('agence')
                ->withCount('adherentsGeres')
                ->orderBy('name')
                ->get();
        } else {
            $agences = Agence::where('active', true)->orderBy('nom')->get();
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->with('agence')
                ->withCount('adherentsGeres')
                ->orderBy('name')
                ->get();
        }

        return view('backoffice.affectations.index', compact('adherents', 'stats', 'agences', 'agents'));
    }

    /**
     * Affecter des adhérents en masse
     */
    public function affecterMasse(Request $request)
    {
        // Décoder le JSON si nécessaire
        $adherentIds = $request->adherent_ids;
        if (is_string($adherentIds)) {
            $adherentIds = json_decode($adherentIds, true);
        }

        $request->merge(['adherent_ids' => $adherentIds]);

        $request->validate([
            'adherent_ids' => 'required|array|min:1',
            'adherent_ids.*' => 'exists:adherents,id',
            'agence_id' => 'nullable|exists:agences,id',
            'agent_ids' => 'nullable|array',
            'agent_ids.*' => 'exists:users,id',
            'is_principal' => 'nullable|boolean',
        ]);

        $affectedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($adherentIds as $adherentId) {
                $adherent = Adherent::find($adherentId);
                
                // Affecter l'agence si fournie
                if ($request->filled('agence_id')) {
                    $adherent->update(['agence_id' => $request->agence_id]);
                }

                // Affecter les agents si fournis
                if ($request->filled('agent_ids')) {
                    foreach ($request->agent_ids as $index => $agentId) {
                        // Vérifier si l'agent est déjà affecté
                        if (!$adherent->agents()->where('agent_id', $agentId)->exists()) {
                            $adherent->agents()->attach($agentId, [
                                'is_principal' => ($index === 0 && $request->boolean('is_principal', true)),
                                'affecte_par' => Auth::id(),
                                'affecte_le' => now(),
                            ]);
                        }
                    }
                }

                $affectedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "{$affectedCount} adhérent(s) affecté(s) avec succès.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affectation: ' . $e->getMessage());
        }
    }

    /**
     * Retirer une affectation d'agent
     */
    public function retirerAgent(Request $request)
    {
        $request->validate([
            'adherent_id' => 'required|exists:adherents,id',
            'agent_id' => 'required|exists:users,id',
        ]);

        $adherent = Adherent::find($request->adherent_id);
        $adherent->agents()->detach($request->agent_id);

        return redirect()->back()->with('success', 'Agent retiré avec succès.');
    }

    /**
     * Vue par agence
     */
    public function parAgence(Request $request, $agenceId = null)
    {
        $agences = Agence::where('active', true)
            ->withCount(['adherents', 'users'])
            ->orderBy('nom')
            ->get();

        $agenceSelectionnee = null;
        $adherents = collect();
        $agents = collect();
        $stats = [];

        if ($agenceId) {
            $agenceSelectionnee = Agence::with(['adherents.agents', 'users'])
                ->findOrFail($agenceId);

            $adherents = $agenceSelectionnee->adherents()
                ->with(['agents', 'agentGestionnaire'])
                ->withCount('agents')
                ->latest()
                ->paginate(30);

            $agents = $agenceSelectionnee->users()
                ->whereIn('role', ['agent', 'chef_service'])
                ->withCount('adherentsGeres')
                ->get();

            $stats = [
                'total_adherents' => $agenceSelectionnee->adherents()->count(),
                'adherents_affectes' => $agenceSelectionnee->adherents()->has('agents')->count(),
                'adherents_non_affectes' => $agenceSelectionnee->adherents()->doesntHave('agents')->count(),
                'agents_actifs' => $agents->count(),
            ];
        }

        return view('backoffice.affectations.par-agence', compact('agences', 'agenceSelectionnee', 'adherents', 'agents', 'stats'));
    }

    /**
     * Vue par agent
     */
    public function parAgent(Request $request, $agentId = null)
    {
        $agents = User::whereIn('role', ['agent', 'chef_service'])
            ->where('active', true)
            ->with('agence')
            ->withCount('adherentsGeres')
            ->orderBy('name')
            ->get();

        $agentSelectionne = null;
        $adherents = collect();
        $stats = [];

        if ($agentId) {
            $agentSelectionne = User::with(['agence', 'adherentsGeres.agence'])
                ->findOrFail($agentId);

            $adherents = $agentSelectionne->adherentsGeres()
                ->with(['agence', 'agents'])
                ->withCount('agents')
                ->when($request->filled('search'), function($q) use ($request) {
                    $search = $request->search;
                    $q->where(function($query) use ($search) {
                        $query->where('nom', 'LIKE', "%{$search}%")
                              ->orWhere('prenom', 'LIKE', "%{$search}%")
                              ->orWhere('membre_id', 'LIKE', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(30);

            $adherentsPrincipaux = $agentSelectionne->adherentsPrincipaux()->count();

            $stats = [
                'total_adherents' => $agentSelectionne->adherentsGeres()->count(),
                'adherents_principaux' => $adherentsPrincipaux,
                'adherents_secondaires' => $agentSelectionne->adherentsGeres()->count() - $adherentsPrincipaux,
            ];
        }

        return view('backoffice.affectations.par-agent', compact('agents', 'agentSelectionne', 'adherents', 'stats'));
    }

    /**
     * Liste des adhérents non affectés
     */
    public function nonAffectes(Request $request)
    {
        $auth = auth()->user();
        $query = Adherent::nonAffectes()
            ->with(['agence', 'agentGestionnaire']);

        if ($auth && $auth->isChefService()) {
            $query->where('agence_id', $auth->agence_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('membre_id', 'LIKE', "%{$search}%");
            });
        }

        $adherents = $query->latest()->paginate(50);

        if ($auth && $auth->isChefService()) {
            $agences = Agence::where('active', true)
                ->where('id', $auth->agence_id)
                ->orderBy('nom')->get();
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->where('agence_id', $auth->agence_id)
                ->with('agence')
                ->orderBy('name')
                ->get();
        } else {
            $agences = Agence::where('active', true)->orderBy('nom')->get();
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->with('agence')
                ->orderBy('name')
                ->get();
        }

        return view('backoffice.affectations.non-affectes', compact('adherents', 'agences', 'agents'));
    }

    /**
     * Définir un agent comme principal
     */
    public function definirAgentPrincipal(Request $request)
    {
        $request->validate([
            'adherent_id' => 'required|exists:adherents,id',
            'agent_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $adherent = Adherent::find($request->adherent_id);

            // Retirer le statut principal des autres agents
            $adherent->agents()->updateExistingPivot(
                $adherent->agents()->pluck('agent_id')->toArray(),
                ['is_principal' => false]
            );

            // Définir le nouvel agent principal
            $adherent->agents()->updateExistingPivot($request->agent_id, ['is_principal' => true]);

            DB::commit();

            return redirect()->back()->with('success', 'Agent principal défini avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erreur lors de la définition de l\'agent principal.');
        }
    }
}
