<?php

namespace App\Http\Controllers;

use App\Models\Epargne;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Traits\FiltersByAgentAdherents;

class EpargneController extends Controller
{
    use FiltersByAgentAdherents;
    /**
     * Afficher la liste des épargnes
     */
    public function index(Request $request)
    {
        $query = Epargne::with('adherent')->latest();
        
        // Filtrer par agent si nécessaire
        $query = $this->applyAgentFilter($query, 'adherent');
        
        // Filtrage par adhérent
        if ($request->has('adherent_id')) {
            $query->where('adherent_id', $request->adherent_id);
        }
        
        // Filtrage par type d'épargne
        if ($request->has('type_epargne')) {
            $query->where('type_epargne', $request->type_epargne);
        }
        
        // Filtrage par statut
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $epargnes = $query->paginate(20);
        
        if ($request->wantsJson()) {
            return response()->json($epargnes);
        }
        
        // Récupérer la liste des adhérents pour le filtre (filtrés par agent)
        $adherents = Adherent::query();
        $adherents = $this->applyAgentFilter($adherents, null); // Utiliser le scope forAgent sur Adherent
        $adherents = $adherents->orderBy('nom')->get();
        
        return view('backoffice.epargnes.index', [
            'epargnes' => $epargnes,
            'adherents' => $adherents,
            'typesEpargne' => Epargne::TYPES_EPARGNE,
            'statuts' => Epargne::STATUTS
        ]);
    }

    /**
     * Afficher le formulaire de création d'une épargne
     */
    public function create()
    {
        $adherents = Adherent::orderBy('nom')->get();
        
        return view('backoffice.epargnes.create', [
            'adherents' => $adherents,
            'typesEpargne' => Epargne::TYPES_EPARGNE
        ]);
    }

    /**
     * Enregistrer une nouvelle épargne
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'adherent_id' => 'required|exists:adherents,id',
            'type_epargne' => 'required|in:epargne_ordinaire,epargne_jeune,epargne_logement,epargne_retraite,epargne_scolaire',
            'montant_initial' => 'required|numeric|min:1000',
            'notes' => 'nullable|string|max:500'
        ]);
        
        // Calculer l'intérêt basé sur le type d'épargne
        $data['taux_interet'] = $this->getTauxInteret($data['type_epargne']);
        $data['interet_cumule'] = 0;
        $data['date_ouverture'] = now();
        $data['statut'] = 'actif';
        
        $epargne = Epargne::create($data);
        
        return redirect()
            ->route('admin.epargnes.show', $epargne)
            ->with('success', 'Le compte épargne a été créé avec succès.');
    }

    /**
     * Afficher les détails d'une épargne
     */
    public function show(Epargne $epargne)
    {
        $epargne->load(['adherent', 'transactions' => function($q) {
            $q->latest();
        }]);
        
        return view('backoffice.epargnes.show', [
            'epargne' => $epargne,
            'typesEpargne' => Epargne::TYPES_EPARGNE
        ]);
    }

    /**
     * Afficher le formulaire de modification d'une épargne
     */
    public function edit(Epargne $epargne)
    {
        $adherents = Adherent::orderBy('nom')->get();
        
        return view('backoffice.epargnes.edit', [
            'epargne' => $epargne,
            'adherents' => $adherents,
            'typesEpargne' => Epargne::TYPES_EPARGNE,
            'statuts' => Epargne::STATUTS
        ]);
    }

    /**
     * Mettre à jour une épargne
     */
    public function update(Request $request, Epargne $epargne)
    {
        $data = $request->validate([
            'type_epargne' => 'required|in:epargne_ordinaire,epargne_jeune,epargne_logement,epargne_retraite,epargne_scolaire',
            'montant_initial' => 'required|numeric|min:1000',
            'notes' => 'nullable|string|max:500',
            'statut' => 'required|in:actif,inactif,bloque,cloture'
        ]);
        
        // Note: Le solde total d'épargne sera calculé dynamiquement via les relations
        
        $epargne->update($data);
        
        return redirect()
            ->route('admin.epargnes.show', $epargne)
            ->with('success', 'Le compte épargne a été mis à jour avec succès.');
    }

    /**
     * Désactiver un compte épargne
     */
    public function destroy(Epargne $epargne)
    {
        // Vérifier qu'il n'y a pas de solde avant de désactiver
        if ($epargne->solde_actuel > 0) {
            return back()->with('error', 'Impossible de désactiver un compte avec un solde positif.');
        }
        
        $epargne->update(['statut' => 'inactif']);
        
        return redirect()
            ->route('admin.epargnes.index')
            ->with('success', 'Le compte épargne a été désactivé avec succès.');
    }
    
    /**
     * Effectuer un dépôt sur un compte épargne
     */
    public function depot(Request $request, Epargne $epargne)
    {
        $data = $request->validate([
            'montant' => 'required|numeric|min:100', // Minimum 100 FCFA
            'date_operation' => 'required|date',
            'moyen_paiement' => 'required|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Créer la transaction
        $transaction = $epargne->transactions()->create([
            'type_operation' => 'depot',
            'montant' => $data['montant'],
            'date_operation' => $data['date_operation'],
            'moyen_paiement' => $data['moyen_paiement'],
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
            'solde_apres_operation' => $epargne->solde_actuel + $data['montant'],
            'auteur_id' => auth()->id(),
        ]);
        
        // Mettre à jour le solde du compte épargne
        $epargne->increment('solde_actuel', $data['montant']);
        
        return back()->with('success', 'Le dépôt a été enregistré avec succès.');
    }
    
    /**
     * Effectuer un retrait sur un compte épargne
     */
    public function retrait(Request $request, Epargne $epargne)
    {
        $data = $request->validate([
            'montant' => [
                'required', 
                'numeric', 
                'min:100', // Minimum 100 FCFA
                'max:' . $epargne->solde_actuel // Ne pas dépasser le solde disponible
            ],
            'date_operation' => 'required|date',
            'motif' => 'required|string|max:500',
            'moyen_paiement' => 'required|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Vérifier si le retrait est possible (solde suffisant)
        if ($epargne->solde_actuel < $data['montant']) {
            return back()->with('error', 'Solde insuffisant pour effectuer ce retrait.');
        }
        
        // Créer la transaction
        $transaction = $epargne->transactions()->create([
            'type_operation' => 'retrait',
            'montant' => $data['montant'],
            'date_operation' => $data['date_operation'],
            'moyen_paiement' => $data['moyen_paiement'],
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? $data['motif'],
            'solde_apres_operation' => $epargne->solde_actuel - $data['montant'],
            'auteur_id' => auth()->id(),
        ]);
        
        // Mettre à jour le solde du compte épargne
        $epargne->decrement('solde_actuel', $data['montant']);
        
        return back()->with('success', 'Le retrait a été effectué avec succès.');
    }
    
    /**
     * Calculer les intérêts pour un compte épargne
     */
    public function calculerInterets(Epargne $epargne)
    {
        // Vérifier si le calcul des intérêts est nécessaire
        if ($epargne->dernier_calcul_interets && 
            $epargne->dernier_calcul_interets->addMonth() > now()) {
            return back()->with('info', 'Les intérêts ont déjà été calculés ce mois-ci.');
        }
        
        // Calculer les intérêts (méthode simplifiée)
        $interets = $epargne->solde_actuel * ($epargne->taux_interet / 100 / 12);
        
        // Créer une transaction d'intérêt
        $transaction = $epargne->transactions()->create([
            'type_operation' => 'interet',
            'montant' => $interets,
            'date_operation' => now(),
            'moyen_paiement' => 'interet',
            'notes' => 'Intérêts mensuels au taux de ' . $epargne->taux_interet . '%',
            'solde_apres_operation' => $epargne->solde_actuel + $interets,
            'auteur_id' => auth()->id(),
        ]);
        
        // Mettre à jour le compte épargne
        $epargne->increment('solde_actuel', $interets);
        $epargne->increment('interet_cumule', $interets);
        $epargne->dernier_calcul_interets = now();
        $epargne->save();
        
        return back()->with('success', "Les intérêts de " . number_format($interets, 0, ',', ' ') . " FCFA ont été ajoutés au compte.");
    }
    
    /**
     * Obtenir le taux d'intérêt en fonction du type d'épargne
     */
    private function getTauxInteret($typeEpargne)
    {
        $taux = [
            'epargne_ordinaire' => 2.5,  // 2.5% par an
            'epargne_jeune' => 3.0,      // 3.0% par an
            'epargne_logement' => 4.0,   // 4.0% par an
            'epargne_retraite' => 3.5,   // 3.5% par an
            'epargne_scolaire' => 3.0,   // 3.0% par an
        ];
        
        return $taux[$typeEpargne] ?? 2.5; // Taux par défaut
    }
    
    /**
     * Exporter la liste des épargnes en Excel
     */
    public function export()
    {
        // TODO: Implémenter l'export Excel
        return response()->json(['message' => 'Export en cours de développement']);
    }
}
