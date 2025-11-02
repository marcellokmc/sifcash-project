<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Plan;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Traits\FiltersByAgentAdherents;

class AdhesionController extends Controller
{
    use FiltersByAgentAdherents;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Adhesion::with(['adherent', 'plan', 'createdByAgent']);
        
        // Filtrer par agent si nécessaire
        $query = $this->applyAgentFilter($query, 'adherent');
        
        // Recherche multicritère
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('adherent', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('membre_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('plan', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrage par plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        
        // Filtrage par période
        if ($request->filled('date_debut')) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_debut', '<=', $request->date_fin);
        }
        
        // Filtrage par montant
        if ($request->filled('montant_min')) {
            $query->where('montant_souscrit', '>=', $request->montant_min);
        }
        if ($request->filled('montant_max')) {
            $query->where('montant_souscrit', '<=', $request->montant_max);
        }
        
        // Filtrage par renouvelable
        if ($request->filled('renouvelable')) {
            $query->where('renouvelable', $request->renouvelable === '1');
        }
        
        $adhesions = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Adhesion::count(),
            'actives' => Adhesion::where('statut', 'actif')->count(),
            'en_attente' => Adhesion::where('statut', 'en_attente_activation')->count(),
            'closes' => Adhesion::where('statut', 'clos')->count(),
        ];
        
        $plans = Plan::all();

        return view('backoffice.adhesions.index', compact('adhesions', 'stats', 'plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = Plan::actif()->get();
        $adherents = Adherent::with('user')
            ->where('statut_compte', 'actif')
            ->get();

        return view('backoffice.adhesions.create', compact('plans', 'adherents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adherent_id' => 'required|exists:adherents,id',
            'plan_id' => 'required|exists:plans,id',
            'montant_souscrit' => 'required|numeric|min:1',
            'date_debut' => 'required|date|after_or_equal:today',
            'renouvelable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);

        // Vérifier si le montant est valide pour le plan
        if (!$plan->isMontantValide($request->montant_souscrit)) {
            return redirect()->back()
                ->with('error', 'Le montant saisi n\'est pas valide pour ce plan.')
                ->withInput();
        }

        // Vérifier si l'adhérent a déjà une adhésion active sur ce plan
        $existingAdhesion = Adhesion::where('adherent_id', $request->adherent_id)
            ->where('plan_id', $request->plan_id)
            ->whereIn('statut', ['actif', 'en_attente_activation'])
            ->first();

        if ($existingAdhesion) {
            return redirect()->back()
                ->with('error', 'Cet adhérent a déjà une adhésion active ou en attente sur ce plan.')
                ->withInput();
        }

        $adhesion = Adhesion::create([
            'adherent_id' => $request->adherent_id,
            'plan_id' => $request->plan_id,
            'montant_souscrit' => $request->montant_souscrit,
            'date_debut' => $request->date_debut,
            'renouvelable' => $request->boolean('renouvelable'),
            'solde_actuel' => $request->montant_souscrit,
            'created_by_agent_id' => auth()->id(),
            'statut' => 'en_attente_activation',
        ]);

        return redirect()->route('admin.adhesions.show', $adhesion)
            ->with('success', 'Adhésion créée avec succès. En attente d\'activation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adhesion $adhesion)
    {
        $adhesion->load([
            'adherent.user', 
            'plan', 
            'paiements.details',
            'renouvellements' => function($query) {
                $query->orderBy('date_renouvellement', 'desc');
            },
            'createdByAgent'
        ]);

        return view('backoffice.adhesions.show', compact('adhesion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adhesion $adhesion)
    {
        if ($adhesion->isActif()) {
            return redirect()->route('admin.adhesions.show', $adhesion)
                ->with('error', 'Impossible de modifier une adhésion active.');
        }

        $plans = Plan::actif()->get();

        return view('backoffice.adhesions.edit', compact('adhesion', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adhesion $adhesion)
    {
        if ($adhesion->isActif()) {
            return redirect()->route('admin.adhesions.show', $adhesion)
                ->with('error', 'Impossible de modifier une adhésion active.');
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'montant_souscrit' => 'required|numeric|min:1',
            'date_debut' => 'required|date|after_or_equal:today',
            'renouvelable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);

        if (!$plan->isMontantValide($request->montant_souscrit)) {
            return redirect()->back()
                ->with('error', 'Le montant saisi n\'est pas valide pour ce plan.')
                ->withInput();
        }

        $adhesion->update([
            'plan_id' => $request->plan_id,
            'montant_souscrit' => $request->montant_souscrit,
            'date_debut' => $request->date_debut,
            'renouvelable' => $request->boolean('renouvelable'),
            'solde_actuel' => $request->montant_souscrit, // Réinitialiser le solde
        ]);

        return redirect()->route('admin.adhesions.show', $adhesion)
            ->with('success', 'Adhésion modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adhesion $adhesion)
    {
        if ($adhesion->isActif()) {
            return redirect()->route('admin.adhesions.show', $adhesion)
                ->with('error', 'Impossible de supprimer une adhésion active.');
        }

        $adhesion->delete();

        return redirect()->route('admin.adhesions.index')
            ->with('success', 'Adhésion supprimée avec succès.');
    }

    /**
     * Activer une adhésion
     */
    public function activate(Adhesion $adhesion)
    {
        if ($adhesion->isActif()) {
            return redirect()->back()
                ->with('error', 'Cette adhésion est déjà active.');
        }

        $adhesion->activer();

        return redirect()->back()
            ->with('success', 'Adhésion activée avec succès.');
    }

    /**
     * Clôturer une adhésion
     */
    public function close(Request $request, Adhesion $adhesion)
    {
        if ($adhesion->isClos()) {
            return redirect()->back()
                ->with('error', 'Cette adhésion est déjà clôturée.');
        }

        $validator = Validator::make($request->all(), [
            'type_cloture' => 'required|in:retrait_anticipé,arrivee_terme',
            'motif' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $adhesion->cloturer($request->motif, $request->type_cloture);

        return redirect()->back()
            ->with('success', 'Adhésion clôturée avec succès.');
    }

    /**
     * Suspendre une adhésion
     */
    public function suspend(Request $request, Adhesion $adhesion)
    {
        if ($adhesion->isSuspendu()) {
            return redirect()->back()
                ->with('error', 'Cette adhésion est déjà suspendue.');
        }

        $validator = Validator::make($request->all(), [
            'motif' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $adhesion->suspendre($request->motif);

        return redirect()->back()
            ->with('success', 'Adhésion suspendue avec succès.');
    }

    /**
     * Reprendre une adhésion suspendue
     */
    public function resume(Adhesion $adhesion)
    {
        if (!$adhesion->isSuspendu()) {
            return redirect()->back()
                ->with('error', 'Cette adhésion n\'est pas suspendue.');
        }

        $adhesion->reprendre();

        return redirect()->back()
            ->with('success', 'Adhésion reprise avec succès.');
    }

    /**
     * Renouveler une adhésion
     */
    public function renew(Request $request, Adhesion $adhesion)
    {
        if (!$adhesion->peutEtreRenouvelee()) {
            return redirect()->back()
                ->with('error', 'Cette adhésion ne peut pas être renouvelée.');
        }

        $validator = Validator::make($request->all(), [
            'montant_souscrit' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = $adhesion->plan;

        if (!$plan->isMontantValide($request->montant_souscrit)) {
            return redirect()->back()
                ->with('error', 'Le montant saisi n\'est pas valide pour ce plan.')
                ->withInput();
        }

        // Créer le renouvellement
        $renouvellement = $adhesion->renouvellements()->create([
            'montant_souscrit' => $request->montant_souscrit,
            'date_renouvellement' => now(),
            'date_debut_periode' => $adhesion->date_fin,
            'date_fin_periode' => $adhesion->calculerDateFin($adhesion->date_fin),
            'taux_interet_applique' => $plan->taux_interet,
            'effectue_par_agent_id' => auth()->id(),
        ]);

        // Mettre à jour l'adhésion
        $adhesion->update([
            'montant_souscrit' => $request->montant_souscrit,
            'solde_actuel' => $request->montant_souscrit,
            'date_debut' => $renouvellement->date_debut_periode,
            'date_fin' => $renouvellement->date_fin_periode,
            'prochaine_echeance' => $adhesion->getProchaineEcheance(),
            'nombre_renouvellements' => $adhesion->nombre_renouvellements + 1,
        ]);

        return redirect()->back()
            ->with('success', 'Adhésion renouvelée avec succès.');
    }

    /**
     * Liste des adhésions pour l'espace adhérent
     */
    public function indexForAdherent(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('info', 'Veuillez compléter votre profil adhérent.');
        }

        $query = Adhesion::with('plan')
            ->where('adherent_id', $adherent->id)
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('plan')) {
            $query->where('plan_id', $request->plan);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        // Pagination intelligente
        $totalAdhesions = $query->count();
        if ($totalAdhesions > 10) {
            $adhesions = $query->paginate(10)->appends($request->query());
        } else {
            $adhesions = $query->get();
        }

        // Calculer les stats sur toutes les adhésions (pas seulement celles paginées)
        $allAdhesions = Adhesion::where('adherent_id', $adherent->id)->get();
        $stats = [
            'total' => $allAdhesions->count(),
            'actives' => $allAdhesions->where('statut', 'actif')->count(),
            'suspendues' => $allAdhesions->where('statut', 'suspendu')->count(),
            'solde_total' => $allAdhesions->sum('solde_actuel'),
        ];

        // Récupérer les plans pour le filtre
        $plans = Plan::actif()->get();

        return view('adherent.adhesions.index', compact('adhesions', 'stats', 'plans'));
    }

    /**
     * Détail d'une adhésion pour l'espace adhérent
     */
    public function showForAdherent(Adhesion $adhesion)
    {
        $this->authorize('view', $adhesion);

        $adhesion->load([
            'plan',
            'paiements.details',
            'renouvellements' => function($query) {
                $query->orderBy('date_renouvellement', 'desc');
            }
        ]);
        
        // Calcul des statistiques
        $totalPaiements = $adhesion->paiements->where('statut', 'validé')->sum('montant');
        $paiementsEnAttente = $adhesion->paiements->where('statut', 'en_attente')->count();
        $dernierPaiement = $adhesion->paiements->where('statut', 'validé')->sortByDesc('date_soumission')->first();
        
        // Calcul du retrait anticipé disponible
        $retraitAnticipe = $adhesion->paiements
            ->where('statut', 'validé')
            ->where('categorie', '!=', 'ouverture')
            ->sum(function($paiement) {
                return $paiement->details
                    ->whereNotIn('type_frais', ['interet', 'dossier', 'entretien'])
                    ->sum('montant');
            });
        
        // Calcul des intérêts accumulés
        $interetsAccumules = $adhesion->paiements
            ->where('statut', 'validé')
            ->sum(function($paiement) {
                return $paiement->details
                    ->where('type_frais', 'interet')
                    ->sum('montant');
            });

        return view('adherent.adhesions.show', compact(
            'adhesion', 
            'totalPaiements', 
            'paiementsEnAttente', 
            'dernierPaiement',
            'retraitAnticipe',
            'interetsAccumules'
        ));
    }

    /**
     * Formulaire de souscription pour l'espace adhérent
     */
    public function createForAdherent()
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('info', 'Veuillez compléter votre profil adhérent.');
        }

        $plans = Plan::actif()->get();

        return view('adherent.adhesions.create', compact('plans', 'adherent'));
    }

    /**
     * Souscription à un plan depuis l'espace adhérent
     */
    public function storeForAdherent(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Veuillez compléter votre profil adhérent.');
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'montant_souscrit' => 'required|numeric|min:1',
            'date_debut' => 'required|date|after_or_equal:today',
            'renouvelable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = Plan::findOrFail($request->plan_id);

        if (!$plan->isMontantValide($request->montant_souscrit)) {
            return redirect()->back()
                ->with('error', 'Le montant saisi n\'est pas valide pour ce plan.')
                ->withInput();
        }

        // Vérifier si l'adhérent a déjà une adhésion active sur ce plan
        $existingAdhesion = Adhesion::where('adherent_id', $adherent->id)
            ->where('plan_id', $request->plan_id)
            ->whereIn('statut', ['actif', 'en_attente_activation'])
            ->first();

        if ($existingAdhesion) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà une adhésion active ou en attente sur ce plan.')
                ->withInput();
        }

        $adhesion = Adhesion::create([
            'adherent_id' => $adherent->id,
            'plan_id' => $request->plan_id,
            'montant_souscrit' => $request->montant_souscrit,
            'date_debut' => $request->date_debut,
            'renouvelable' => $request->boolean('renouvelable'),
            'solde_actuel' => $request->montant_souscrit,
            'statut' => 'en_attente_activation',
        ]);

        return redirect()->route('adherent.adhesions.show', $adhesion)
            ->with('success', 'Votre souscription a été enregistrée. Elle sera activée après validation.');
    }

    /**
     * Télécharger les détails de l'adhésion en PDF
     */
    public function download(Adhesion $adhesion)
    {
        $adhesion->load(['adherent.user', 'plan', 'createdByAgent']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.adhesion-details', compact('adhesion'));
        
        return $pdf->download('adhesion-' . $adhesion->id . '-details.pdf');
    }

    /**
     * Télécharger l'adhésion avec tous ses paiements en PDF
     */
    public function downloadWithPayments(Adhesion $adhesion)
    {
        $adhesion->load([
            'adherent.user', 
            'plan', 
            'paiements.details',
            'paiements.validatedByAgent',
            'createdByAgent'
        ]);

        // Calcul du retrait anticipé
        $retraitAnticipe = $adhesion->paiements
            ->where('statut', 'validé')
            ->where('categorie', '!=', 'ouverture')
            ->sum(function($paiement) {
                return $paiement->details
                    ->whereNotIn('type_frais', ['interet', 'dossier', 'entretien'])
                    ->sum('montant');
            });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.adhesion-with-payments', compact('adhesion', 'retraitAnticipe'));
        
        return $pdf->download('adhesion-' . $adhesion->id . '-paiements.pdf');
    }
}
