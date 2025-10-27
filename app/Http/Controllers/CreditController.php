<?php

namespace App\Http\Controllers;
use App\Models\Credit;
use App\Models\Adherent;
use App\Models\EcheanceCredit;
use App\Models\Agence;
use App\Models\PaiementCredit;
use App\Models\PaiementCreditPreuve;
use Illuminate\Http\Request;
use App\Services\CreditScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class CreditController extends Controller
{
    private CreditScheduleService $scheduleService;

    public function __construct(CreditScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Afficher le formulaire de demande de crédit
     */
    public function create()
    {
        $this->authorize('create', Credit::class);
        
        // Récupérer les conditions d'éligibilité actives
        $conditions = \App\Models\ConditionEligibiliteCredit::where('actif', true)->get();
        
        return view('adherent.credits.create', [
            'conditions' => $conditions
        ]);
    }

    public function recordPayment(Request $request, Credit $credit)
    {
        $this->authorize('recordPayment', $credit);

        $data = $request->validate([
            'echeance_id' => ['required','integer','exists:echeance_credits,id'],
            'date_paiement' => ['required','date'],
            'montant' => ['required','numeric','min:0.01'],
            'mode' => ['nullable','string'],
            'reference' => ['nullable','string'],
            'preuves.*' => ['nullable','file'],
        ]);

        $echeance = EcheanceCredit::with('credit.adherent.user')
            ->where('credit_id', $credit->id)
            ->findOrFail($data['echeance_id']);

        $dueTotal = (float)$echeance->montant_attendu + max(0, (float)$echeance->penalite_appliquee);
        $penaliteDue = max(0, (float)$echeance->penalite_appliquee);
        $amount = round((float)$data['montant'], 2);
        $penalitePaid = min($amount, $penaliteDue);
        $principalPaid = max(0.0, $amount - $penalitePaid);

        $paiement = null;

        DB::transaction(function () use ($credit, $echeance, $data, $request, $penalitePaid, $principalPaid, $amount, &$paiement) {
            // Create payment record first
            $paiement = PaiementCredit::create([
                'credit_id' => $credit->id,
                'echeance_credit_id' => $echeance->id,
                'date_paiement' => $data['date_paiement'],
                'montant' => $amount,
                'penalite' => round($penalitePaid, 2),
                'mode' => $data['mode'] ?? null,
                'reference' => $data['reference'] ?? null,
                'received_by_agent_id' => $request->user()->id ?? null,
                'statut' => 'valide',
                'motif_rejet' => null,
            ]);

            // Update installment amounts
            $echeance->montant_paye = round(((float)$echeance->montant_paye) + $principalPaid, 2);

            $totalPaidAgainstDue = (float)$echeance->montant_paye + (float)$penalitePaid;
            $totalDue = (float)$echeance->montant_attendu + max(0, (float)$echeance->penalite_appliquee);
            if ($totalPaidAgainstDue + 0.001 >= $totalDue) {
                $echeance->statut = 'payé';
                $echeance->date_paiement = $data['date_paiement'];
            }
            $echeance->save();

            // Upload proofs if any
            if ($request->hasFile('preuves')) {
                foreach ($request->file('preuves') as $file) {
                    $path = $file->store('paiements_preuves');
                    PaiementCreditPreuve::create([
                        'paiement_credit_id' => $paiement->id,
                        'type' => $file->getClientOriginalExtension(),
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        // Notify adherent user after commit
        $user = $credit->adherent?->user;
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'titre' => 'Paiement crédit enregistré',
                'message' => 'Votre paiement de '.number_format((float)$data['montant'], 2, ',', ' ').' a été enregistré pour l\'échéance du '.$echeance->date_echeance.'.',
                'lu' => false,
                'type' => 'info',
            ]);
        }

        // Gérer la réponse selon le type de requête
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Paiement enregistré.']);
        }

        return redirect()->back()->with('success', 
            'Paiement de ' . number_format((float)$data['montant'], 0, ',', ' ') . ' FCFA enregistré avec succès pour l\'échéance du ' . $echeance->date_echeance->format('d/m/Y') . '.'  
        );
    }

    /**
     * Adhérent: créer une demande de crédit
     */
    public function store(Request $request)
    {
        $this->authorize('create', Credit::class);

        $validated = $request->validate([
            'montant' => ['required', 'numeric', 'min:1000'], // Montant minimum de 1000 FCFA
            'duree' => ['required', 'integer', 'min:1', 'max:60'], // Durée en mois (max 5 ans)
            'motif' => ['required', 'string', 'max:1000'],
            'garanties' => ['nullable', 'string', 'max:1000'],
            'documents.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // Max 5MB par fichier
        ]);

        // Récupérer l'adhérent connecté
        $adherent = $request->user()?->adherent;
        if (!$adherent) {
            return redirect()->back()->with('error', 'Profil adhérent introuvable.');
        }

        // Vérifier si l'adhérent a déjà une demande en cours
        $hasPendingCredit = $adherent->credits()
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->exists();

        if ($hasPendingCredit) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande de crédit en cours de traitement.');
        }

        // Calculer le taux d'intérêt en fonction de la durée
        $tauxInteret = $this->calculateInterestRate($validated['duree']);
        
        // Calculer les frais de dossier (2% du montant demandé, minimum 5000 FCFA)
        $fraisDossier = max(5000, $validated['montant'] * 0.02);

        // Créer la demande de crédit
        $credit = new Credit([
            'adherent_id' => $adherent->id,
            'montant_demande' => $validated['montant'],
            'montant_accorde' => 0, // À définir lors de l'approbation
            'duree' => $validated['duree'],
            'taux' => $tauxInteret,
            'type_credit' => 'personnel', // Valeur par défaut
            'periodicite' => 'mensuel', // Valeur par défaut
            'frais_dossier' => $fraisDossier,
            'statut' => 'en_attente',
            'etat' => 'soumis',
            'date_demande' => now()->toDateString(),
            'motif' => $validated['motif'],
            'garanties' => $validated['garanties'] ?? null,
        ]);

        // Enregistrer le crédit en base de données
        $credit->save();

        // Gérer le téléchargement des documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents/credits/' . $credit->id, 'public');
                
                $document = new \App\Models\Document([
                    'nom' => $file->getClientOriginalName(),
                    'chemin' => $path,
                    'type' => $file->getClientMimeType(),
                    'taille' => $file->getSize(),
                    'documentable_id' => $credit->id,
                    'documentable_type' => Credit::class,
                ]);
                
                $credit->documents()->save($document);
            }
        }

        // Envoyer une notification à l'administrateur
        Notification::create([
            'titre' => 'Nouvelle demande de crédit',
            'contenu' => "L'adhérent {$adherent->nom_complet} a soumis une demande de crédit de {$validated['montant']} FCFA.",
            'type' => 'nouvelle_demande_credit',
            'lien' => route('admin.credits.show', $credit->id),
            'destinataire_id' => 1, // ID de l'administrateur
            'statut' => 'non_lu',
        ]);

        return redirect()->route('adherent.credits.index')
            ->with('success', 'Votre demande de crédit a été soumise avec succès. Vous serez notifié de son évolution.');
    }

    /**
     * Calcule le taux d'intérêt en fonction de la durée du crédit
     */
    private function calculateInterestRate($dureeMois)
    {
        if ($dureeMois <= 12) {
            return 10.0; // 10% pour les crédits jusqu'à 1 an
        } elseif ($dureeMois <= 24) {
            return 12.5; // 12.5% pour les crédits de 1 à 2 ans
        } elseif ($dureeMois <= 36) {
            return 15.0; // 15% pour les crédits de 2 à 3 ans
        } else {
            return 18.0; // 18% pour les crédits de plus de 3 ans
        }
    }

    /**
     * Admin/Agent: approuver un crédit
     */
    public function approve(Request $request, Credit $credit)
    {
        $this->authorize('approve', $credit);

        $data = $request->validate([
            'montant_accorde' => ['required','numeric','min:0.01'],
            'taux' => ['nullable','numeric','min:0'],
        ]);

        if (!in_array($credit->etat, ['en_examen','soumis'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Crédit non éligible pour approbation.'], 422);
            }
            return redirect()->back()->with('error', 'Crédit non éligible pour approbation.');
        }

        $credit->montant_accorde = $data['montant_accorde'];
        if (array_key_exists('taux', $data) && $data['taux'] !== null) {
            $credit->taux = $data['taux'];
        }
        $credit->statut = 'approuvé';
        $credit->etat = 'approuve';
        $credit->date_validation = now()->toDateString();
        $credit->validated_by_agent_id = $request->user()->id ?? null;
        $credit->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Crédit approuvé.']);
        }

        return redirect()->route('admin.credits.show', $credit)
            ->with('success', 'Crédit approuvé avec succès.');
    }

    /**
     * Admin/Agent: rejeter un crédit
     */
    public function reject(Request $request, Credit $credit)
    {
        $this->authorize('reject', $credit);

        $data = $request->validate([
            'motif_rejet' => ['required','string'],
        ]);

        if (!in_array($credit->etat, ['en_examen','soumis'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Crédit non éligible pour rejet.'], 422);
            }
            return redirect()->back()->with('error', 'Crédit non éligible pour rejet.');
        }

        $credit->statut = 'rejeté';
        $credit->etat = 'rejete';
        $credit->motif_rejet = $data['motif_rejet'];
        $credit->validated_by_agent_id = $request->user()->id ?? null;
        $credit->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Crédit rejeté.']);
        }

        return redirect()->route('admin.credits.show', $credit)
            ->with('success', 'Crédit rejeté.');
    }

    /**
     * Admin/Agent: valider le contrat et définir la date de début
     */
    public function contract(Request $request, Credit $credit)
    {
        $this->authorize('contract', $credit);

        $data = $request->validate([
            'date_debut_remboursement' => ['required','date'],
        ]);

        if ($credit->etat !== 'approuve') {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Le crédit doit être approuvé avant le contrat.'], 422);
            }
            return redirect()->back()->with('error', 'Le crédit doit être approuvé avant le contrat.');
        }

        $credit->etat = 'contrat';
        $credit->date_debut_remboursement = $data['date_debut_remboursement'];
        $credit->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Contrat validé. Vous pouvez générer l\'échéancier.']);
        }

        return redirect()->route('admin.credits.show', $credit)
            ->with('success', 'Contrat validé. Vous pouvez maintenant générer l\'échéancier.');
    }

    /**
     * Admin/Agent: générer l'échéancier et activer le crédit
     */
    public function generateSchedule(Request $request, Credit $credit)
    {
        $this->authorize('generateSchedule', $credit);

        $request->validate([
            'date_debut_remboursement' => ['nullable','date'],
        ]);

        if ($request->filled('date_debut_remboursement')) {
            $credit->date_debut_remboursement = $request->date('date_debut_remboursement');
        }

        if (!in_array($credit->etat, ['approuve','contrat','actif'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Crédit non éligible pour génération d\'échéancier.'], 422);
            }
            return redirect()->back()->with('error', 'Crédit non éligible pour génération d\'échéancier.');
        }

        try {
            // Generate schedule via service
            $this->scheduleService->generateSchedule($credit);

            $credit->etat = 'actif';
            $credit->save();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Échéancier généré avec succès.']);
            }

            return redirect()->route('admin.credits.show', $credit)
                ->with('success', 'Échéancier généré avec succès. Le crédit est maintenant actif.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération de l\'échéancier: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Erreur lors de la génération de l\'échéancier.'], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la génération de l\'échéancier.');
        }
    }

    /**
     * Afficher la liste des crédits en retard de paiement
     */
    public function creditsEnRetard(Request $request)
    {
        // Test temporaire de debug
        \Log::info('creditsEnRetard appelée, user: ' . (auth()->check() ? auth()->user()->role : 'non connecté'));
        
        try {
            // Récupérer les crédits ayant au moins une échéance en retard
            $credits = Credit::whereHas('echeances', function($query) {
                    $query->where('date_echeance', '<', now())
                          ->where('statut', '!=', 'paye');
                })
                ->with(['adherent', 'echeances' => function($query) {
                    $query->where('date_echeance', '<', now())
                          ->where('statut', '!=', 'paye')
                          ->orderBy('date_echeance');
                }])
                ->latest()
                ->paginate(15);

            if ($request->wantsJson()) {
                return response()->json($credits);
            }

            return view('backoffice.credits.retard', compact('credits'));
        } catch (\Exception $e) {
            \Log::error('Erreur creditsEnRetard: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->route('admin.credits.index')
                ->with('error', 'Erreur lors du chargement des crédits en retard: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les rapports et statistiques des crédits
     */
    public function rapports(Request $request)
    {
        // Statistiques générales
        $stats = [
            'total_credits' => Credit::count(),
            'total_montant' => Credit::sum('montant_accorde'),
            'moyenne_montant' => Credit::avg('montant_accorde'),
            'taux_remboursement' => 0, // À calculer
            'credits_par_statut' => \DB::table('credits')
                ->select('statut', \DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->pluck('total', 'statut'),
            'credits_par_mois' => \DB::table('credits')
                ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'), \DB::raw('count(*) as total'))
                ->groupBy('mois')
                ->orderBy('mois')
                ->get(),
        ];

        // Calcul du taux de remboursement
        $totalEcheances = \App\Models\EcheanceCredit::count();
        $echeancesPayees = \App\Models\EcheanceCredit::where('statut', 'paye')->count();
        $stats['taux_remboursement'] = $totalEcheances > 0 
            ? round(($echeancesPayees / $totalEcheances) * 100, 2) 
            : 0;

        // Derniers crédits accordés
        $derniersCredits = Credit::with('adherent')
            ->where('statut', 'approuvé')
            ->whereNotNull('date_validation')
            ->latest('date_validation')
            ->take(5)
            ->get();

        // Crédits avec les plus gros montants
        $plusGrosCredits = Credit::with('adherent')
            ->whereNotNull('montant_accorde')
            ->orderBy('montant_accorde', 'desc')
            ->take(5)
            ->get();

        return view('backoffice.credits.rapports', [
            'stats' => $stats,
            'derniersCredits' => $derniersCredits,
            'plusGrosCredits' => $plusGrosCredits,
        ]);
    }

    // Admin: list all credits (paginated)
    public function index(Request $request)
    {
        $query = Credit::with(['adherent']);
        
        // Filtrage par statut si spécifié
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrage par état si spécifié
        if ($request->has('etat')) {
            $query->where('etat', $request->etat);
        }
        
        // Filtrage par recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('montant_demande', 'like', "%{$search}%")
                  ->orWhere('statut', 'like', "%{$search}%")
                  ->orWhereHas('adherent', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%");
                  });
            });
        }
        
        $items = $query->latest()->paginate(20);
        
        if ($request->wantsJson()) {
            return response()->json($items);
        }
        
        return view('backoffice.credits.index', compact('items'));
    }
    
    /**
     * Afficher un crédit spécifique (Admin)
     */
    public function show(Credit $credit)
    {
        $credit->load([
            'adherent.user',
            'echeances' => function($query) {
                $query->orderBy('date_echeance');
            },
            'paiements.preuves',
            'garanties'
        ]);
        
        return view('backoffice.credits.show', compact('credit'));
    }
    
    /**
     * Espace adhérent: lister ses crédits
     */
    public function indexForAdherent()
    {
        $adherent = auth()->user()->adherent;
        
        if (!$adherent) {
            return redirect()->route('adherent.inscription');
        }
        
        $credits = $adherent->credits()
            ->with(['echeances' => function($query) {
                $query->orderBy('date_echeance');
            }])
            ->latest()
            ->paginate(10);
        
        return view('adherent.credits.index', compact('credits'));
    }
    
    /**
     * Espace adhérent: afficher un crédit
     */
    public function showForAdherent(Credit $credit)
    {
        $this->authorize('view', $credit);
        
        $credit->load([
            'echeances' => function($query) {
                $query->orderBy('date_echeance');
            },
            'paiements.preuves'
        ]);
        
        return view('adherent.credits.show', compact('credit'));
    }

    // Admin: list payments of a credit
    public function payments(Credit $credit)
    {
        $this->authorize('view', $credit);
        $items = $credit->paiements()->with('preuves')->latest()->paginate(20);
        return response()->json($items);
    }
    
    /**
     * Espace adhérent: lister ses paiements de crédit
     */
    public function indexPaiementsForAdherent()
    {
        $adherent = auth()->user()->adherent;
        
        if (!$adherent) {
            return redirect()->route('adherent.inscription');
        }
        
        // Récupérer tous les paiements de crédit de l'adhérent
        $paiements = PaiementCredit::whereHas('credit', function($query) use ($adherent) {
            $query->where('adherent_id', $adherent->id);
        })
        ->with(['credit.adherent', 'echeance', 'preuves'])
        ->latest()
        ->paginate(15);
        
        return view('adherent.credits.paiements.index', compact('paiements'));
    }
    
    /**
     * Espace adhérent: afficher un paiement de crédit
     */
    public function showPaiementForAdherent(PaiementCredit $paiement)
    {
        // Vérifier que le paiement appartient à l'adhérent connecté
        $adherent = auth()->user()->adherent;
        if ($paiement->credit->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $paiement->load(['credit', 'echeance', 'preuves']);
        
        return view('adherent.credits.paiements.show', compact('paiement'));
    }
    
    /**
     * Espace adhérent: afficher les preuves d'un paiement
     */
    public function showPreuvesForAdherent(PaiementCredit $paiement)
    {
        // Vérifier que le paiement appartient à l'adhérent connecté
        $adherent = auth()->user()->adherent;
        if ($paiement->credit->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $preuves = $paiement->preuves;
        
        if (request()->wantsJson()) {
            $html = view('adherent.credits.paiements.partials.preuves', compact('preuves'))->render();
            return response()->json(['html' => $html]);
        }
        
        return view('adherent.credits.paiements.preuves', compact('paiement', 'preuves'));
    }
    
    /**
     * Espace adhérent: télécharger une preuve de paiement
     */
    public function downloadPreuveForAdherent(PaiementCreditPreuve $preuve)
    {
        // Vérifier que la preuve appartient à l'adhérent connecté
        $adherent = auth()->user()->adherent;
        if ($preuve->paiement->credit->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $filePath = storage_path('app/' . $preuve->path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé.');
        }
        
        return response()->download($filePath, $preuve->original_name);
    }
    
    /**
     * Espace adhérent: soumettre un paiement de crédit
     */
    public function submitPaymentForAdherent(Request $request, Credit $credit)
    {
        $adherent = auth()->user()->adherent;
        
        // Vérifier que le crédit appartient à l'adhérent
        if ($credit->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $data = $request->validate([
            'echeance_id' => ['required','integer','exists:echeance_credits,id'],
            'date_paiement' => ['required','date'],
            'montant' => ['required','numeric','min:0.01'],
            'mode' => ['nullable','string'],
            'reference' => ['nullable','string'],
            'preuves.*' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'], // Max 5MB
        ]);
        
        $echeance = EcheanceCredit::where('credit_id', $credit->id)
            ->findOrFail($data['echeance_id']);
        
        $amount = round((float)$data['montant'], 2);
        
        DB::transaction(function () use ($credit, $echeance, $data, $request, $amount) {
            // Créer le paiement en attente de validation
            $paiement = PaiementCredit::create([
                'credit_id' => $credit->id,
                'echeance_credit_id' => $echeance->id,
                'date_paiement' => $data['date_paiement'],
                'montant' => $amount,
                'penalite' => 0, // Sera calculée par l'agent
                'mode' => $data['mode'] ?? null,
                'reference' => $data['reference'] ?? null,
                'received_by_agent_id' => null, // Sera défini lors de la validation
                'statut' => 'en_attente', // En attente de validation
                'motif_rejet' => null,
            ]);
            
            // Upload des preuves si fournies
            if ($request->hasFile('preuves')) {
                foreach ($request->file('preuves') as $file) {
                    $path = $file->store('paiements_preuves');
                    PaiementCreditPreuve::create([
                        'paiement_credit_id' => $paiement->id,
                        'type' => $file->getClientOriginalExtension(),
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });
        
        // Notifier les agents
        $agents = \App\Models\User::whereIn('role', ['admin', 'agent'])->get();
        foreach ($agents as $agent) {
            Notification::create([
                'user_id' => $agent->id,
                'titre' => 'Nouveau paiement à valider',
                'message' => 'L\'adhérent ' . $adherent->nom_complet . ' a soumis un paiement de ' . number_format($amount, 0, ',', ' ') . ' FCFA pour validation.',
                'lu' => false,
                'type' => 'info',
            ]);
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Votre paiement a été soumis avec succès. Il sera validé par un agent sous peu.'
            ]);
        }
        
        return redirect()->back()->with('success', 
            'Votre paiement a été soumis avec succès. Il sera validé par un agent sous peu.'
        );
    }

    /**
     * Export du contrat de crédit en PDF
     */
    public function exportContract(Credit $credit, Request $request)
    {
        $this->authorize('view', $credit);
        
        $credit->load('adherent.user', 'adherent');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backoffice.credits.exports.contract-pdf', [
            'credit' => $credit,
            'date' => now()->format('d/m/Y')
        ]);
        
        return $pdf->download('contrat_credit_' . $credit->id . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export de l'échéancier (PDF ou Excel)
     */
    public function exportSchedule(Credit $credit, Request $request)
    {
        $this->authorize('view', $credit);
        
        $credit->load([
            'adherent',
            'echeances' => function($query) {
                $query->orderBy('date_echeance');
            }
        ]);
        
        $format = $request->get('format', 'pdf');
        
        if ($format === 'excel') {
            return $this->exportScheduleExcel($credit);
        }
        
        return $this->exportSchedulePDF($credit);
    }

    /**
     * Export de l'échéancier en PDF
     */
    private function exportSchedulePDF(Credit $credit)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backoffice.credits.exports.schedule-pdf', [
            'credit' => $credit,
            'date' => now()->format('d/m/Y')
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('echeancier_credit_' . $credit->id . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export de l'échéancier en Excel (CSV)
     */
    private function exportScheduleExcel(Credit $credit)
    {
        $filename = 'echeancier_credit_' . $credit->id . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($credit) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-tête du fichier
            fputcsv($file, ['', '', 'ECHÉANCIER DE CRÉDIT - SIFCash-Burkina FASO']);
            fputcsv($file, ['Crédit N°', $credit->id]);
            fputcsv($file, ['Adhérent', $credit->adherent->nom_complet ?? 'N/A']);
            fputcsv($file, ['Montant', number_format($credit->montant_accorde, 0, ',', ' ') . ' FCFA']);
            fputcsv($file, ['']);
            
            // Headers des colonnes
            fputcsv($file, ['N°', 'Date Échéance', 'Montant Attendu', 'Montant Payé', 'Pénalité', 'Statut', 'Date Paiement']);
            
            foreach ($credit->echeances as $index => $echeance) {
                fputcsv($file, [
                    $index + 1,
                    $echeance->date_echeance ? \Carbon\Carbon::parse($echeance->date_echeance)->format('d/m/Y') : 'N/A',
                    number_format($echeance->montant_attendu, 0, ',', ' '),
                    number_format($echeance->montant_paye, 0, ',', ' '),
                    number_format($echeance->penalite_appliquee ?? 0, 0, ',', ' '),
                    ucfirst($echeance->statut),
                    $echeance->date_paiement ? \Carbon\Carbon::parse($echeance->date_paiement)->format('d/m/Y') : 'En attente'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export des paiements (PDF ou Excel)
     */
    public function exportPayments(Credit $credit, Request $request)
    {
        $this->authorize('view', $credit);
        
        $credit->load([
            'adherent',
            'paiements' => function($query) {
                $query->orderBy('date_paiement', 'desc');
            },
            'paiements.echeance'
        ]);
        
        $format = $request->get('format', 'pdf');
        
        if ($format === 'excel') {
            return $this->exportPaymentsExcel($credit);
        }
        
        return $this->exportPaymentsPDF($credit);
    }

    /**
     * Export des paiements en PDF
     */
    private function exportPaymentsPDF(Credit $credit)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backoffice.credits.exports.payments-pdf', [
            'credit' => $credit,
            'date' => now()->format('d/m/Y')
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('paiements_credit_' . $credit->id . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export des paiements en Excel (CSV)
     */
    private function exportPaymentsExcel(Credit $credit)
    {
        $filename = 'paiements_credit_' . $credit->id . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($credit) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-tête du fichier
            fputcsv($file, ['', '', 'HISTORIQUE DES PAIEMENTS - SIFCash-Burkina FASO']);
            fputcsv($file, ['Crédit N°', $credit->id]);
            fputcsv($file, ['Adhérent', $credit->adherent->nom_complet ?? 'N/A']);
            fputcsv($file, ['']);
            
            // Headers des colonnes
            fputcsv($file, ['Date Paiement', 'Montant', 'Pénalité', 'Mode', 'Référence', 'Statut']);
            
            foreach ($credit->paiements as $paiement) {
                fputcsv($file, [
                    $paiement->date_paiement,
                    number_format($paiement->montant, 0, ',', ' '),
                    number_format($paiement->penalite ?? 0, 0, ',', ' '),
                    $paiement->mode ?? 'N/A',
                    $paiement->reference ?? 'N/A',
                    ucfirst($paiement->statut ?? 'en_attente')
                ]);
            }
            
            // Totaux
            fputcsv($file, ['']);
            fputcsv($file, ['TOTAL PAYÉ', number_format($credit->paiements->sum('montant'), 0, ',', ' ') . ' FCFA']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
