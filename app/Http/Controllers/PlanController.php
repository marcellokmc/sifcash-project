<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::withCount(['adhesions' => function($query) {
            $query->where('statut', 'actif');
        }])
        ->orderBy('ordre_affichage')
        ->get();

        return view('backoffice.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodicites = [
            'journalier' => 'Journalier',
            'hebdomadaire' => 'Hebdomadaire', 
            'mensuel' => 'Mensuel',
            'trimestriel' => 'Trimestriel',
            'semestriel' => 'Semestriel',
            'annuel' => 'Annuel'
        ];

        return view('backoffice.plans.create', compact('periodicites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:plans,nom',
            'type_plan' => 'required|in:epargne,credit',
            'description' => 'nullable|string',
            'periodicite' => 'required|in:journalier,hebdomadaire,mensuel,trimestriel,semestriel,annuel',
            'montant_min' => 'nullable|numeric|min:0',
            'montant_max' => 'nullable|numeric|min:0|gt:montant_min',
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_min_jours' => 'required|integer|min:1',
            'duree_max_jours' => 'nullable|integer|min:1|gt:duree_min_jours',
            'frais_adhesion' => 'required|numeric|min:0',
            'frais_retrait' => 'required|numeric|min:0',
            'ordre_affichage' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::create($request->all());

        return redirect()->route('admin.plans.show', $plan)
            ->with('success', 'Plan créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        $plan->load(['adhesions.adherent', 'adhesions' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $stats = [
            'total_adhesions' => $plan->adhesions->count(),
            'adhesions_actives' => $plan->adhesions->where('statut', 'actif')->count(),
            'adhesions_en_attente' => $plan->adhesions->where('statut', 'en_attente_activation')->count(),
            'montant_total_souscrit' => $plan->adhesions->sum('montant_souscrit'),
            'solde_total' => $plan->adhesions->sum('solde_actuel'),
        ];

        return view('backoffice.plans.show', compact('plan', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        $periodicites = [
            'journalier' => 'Journalier',
            'hebdomadaire' => 'Hebdomadaire',
            'mensuel' => 'Mensuel',
            'trimestriel' => 'Trimestriel',
            'semestriel' => 'Semestriel',
            'annuel' => 'Annuel'
        ];

        return view('backoffice.plans.edit', compact('plan', 'periodicites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:plans,nom,' . $plan->id,
            'type_plan' => 'required|in:epargne,credit',
            'description' => 'nullable|string',
            'periodicite' => 'required|in:journalier,hebdomadaire,mensuel,trimestriel,semestriel,annuel',
            'montant_min' => 'nullable|numeric|min:0',
            'montant_max' => 'nullable|numeric|min:0|gt:montant_min',
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_min_jours' => 'required|integer|min:1',
            'duree_max_jours' => 'nullable|integer|min:1|gt:duree_min_jours',
            'frais_adhesion' => 'required|numeric|min:0',
            'frais_retrait' => 'required|numeric|min:0',
            'ordre_affichage' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan->update($request->all());

        return redirect()->route('admin.plans.show', $plan)
            ->with('success', 'Plan modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        // Vérifier s'il y a des adhésions actives
        if ($plan->adhesions()->where('statut', 'actif')->exists()) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Impossible de supprimer ce plan car il a des adhésions actives.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan supprimé avec succès.');
    }

    /**
     * Activer un plan
     */
    public function activate(Plan $plan)
    {
        $plan->update(['actif' => true]);

        return redirect()->back()
            ->with('success', 'Plan activé avec succès.');
    }

    /**
     * Désactiver un plan
     */
    public function deactivate(Plan $plan)
    {
        $plan->update(['actif' => false]);

        return redirect()->back()
            ->with('success', 'Plan désactivé avec succès.');
    }

    /**
     * Liste des plans pour l'espace adhérent
     */
    public function listForAdherent(Request $request)
    {
        $query = Plan::actif()
            ->with(['adhesions' => function($q) {
                $q->where('adherent_id', auth()->user()->adherent?->id);
            }])
            ->withCount(['adhesions' => function($q) {
                $q->where('statut', 'actif');
            }])
            ->orderBy('ordre_affichage');

        // Filtres
        if ($request->filled('periodicite')) {
            $query->where('periodicite', $request->periodicite);
        }

        if ($request->filled('montant')) {
            $query->where('montant_max', '>=', $request->montant);
        }

        // Pagination ou collection simple selon le nombre d'éléments
        $totalPlans = $query->count();
        if ($totalPlans > 12) {
            $plans = $query->paginate(12)->appends($request->query());
        } else {
            $plans = $query->get();
        }

        return view('adherent.plans.index', compact('plans'));
    }

    /**
     * Détail d'un plan pour l'espace adhérent
     */
    public function showForAdherent(Plan $plan)
    {
        if (!$plan->actif) {
            return redirect()->route('adherent.plans.index')
                ->with('error', 'Ce plan n\'est plus disponible.');
        }

        return view('adherent.plans.show', compact('plan'));
    }
}