<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agence;
use App\Models\Adherent;
use App\Models\AyantDroit;
use App\Models\Document;
use App\Models\LogConnexion;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\FiltersByAgentAdherents;

class DashboardController extends Controller
{
    use FiltersByAgentAdherents;
    /**
     * Dashboard Administrateur
     */
    public function adminDashboard()
    {
        // Rediriger les agents vers leur dashboard spécifique
        $user = auth()->user();
        if ($user && in_array($user->role, ['agent', 'chef_service'])) {
            return redirect()->route('agent.dashboard');
        }
        
        $stats = [
            'total_users' => User::count(),
            'total_agences' => Agence::count(),
            'active_agences' => Agence::where('active', true)->count(),
            'recent_logs' => LogConnexion::whereDate('created_at', today())->count(),
            
            // Statistiques Module 2 & 3
            'total_adherents' => Adherent::count(),
            'adherents_actifs' => Adherent::where('statut_compte', 'actif')->count(),
            'adherents_en_attente' => Adherent::where('statut_compte', 'en_attente_de_verification')->count(),
            'adherents_inactifs' => Adherent::where('statut_compte', 'inactif')->count(),
            'documents_en_attente' => Document::where('statut', 'soumis')->count(),
            'ayants_droit_en_attente' => AyantDroit::where('statut_validation', 'en_attente')->count(),
            'documents_valides' => Document::where('statut', 'validé')->count(),
            'documents_rejetes' => Document::where('statut', 'rejeté')->count(),
        ];

        $recentLogs = LogConnexion::with('user.agence')
            ->latest()
            ->take(10)
            ->get();

        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        return view('backoffice.dashboard.admin', compact('stats', 'recentLogs', 'usersByRole'));
    }

    /**
     * Dashboard Agent
     */
    public function agentDashboard()
    {
        $user = auth()->user();
        $agence = $user->agence;

        // Récupérer UNIQUEMENT les adhérents affectés à l'agent
        $adherentsQuery = Adherent::with('user');
        $adherentsQuery = $this->applyAgentFilter($adherentsQuery, null);

        $stats = [
            'total_adherents' => (clone $adherentsQuery)->count(),
            'adherents_actifs' => (clone $adherentsQuery)->where('statut_compte', 'actif')->count(),
            'adherents_en_attente' => (clone $adherentsQuery)->where('statut_compte', 'en_attente_de_verification')->count(),
        ];
        
        // Documents en attente pour les adhérents affectés
        $documentsQuery = Document::query();
        $documentsQuery = $this->applyAgentFilter($documentsQuery, 'adherent');
        $stats['documents_en_attente'] = (clone $documentsQuery)->where('statut', 'soumis')->count();
        
        // Ayants droit en attente pour les adhérents affectés
        $ayantsDroitQuery = AyantDroit::query();
        $ayantsDroitQuery = $this->applyAgentFilter($ayantsDroitQuery, 'adherent');
        $stats['ayants_droit_en_attente'] = (clone $ayantsDroitQuery)->where('statut_validation', 'en_attente')->count();
        
        // Crédits pour les adhérents affectés
        $creditsQuery = Credit::query();
        $creditsQuery = $this->applyAgentFilter($creditsQuery, 'adherent');
        $stats['credits_en_attente'] = (clone $creditsQuery)->where('statut', 'en_attente')->count();
        $stats['credits_approuves'] = (clone $creditsQuery)->where('statut', 'approuvé')->count();
        
        // Activité récente de l'agent
        $stats['recent_activity'] = LogConnexion::where('user_id', $user->id)
                                    ->whereDate('created_at', today())
                                    ->count();

        // Récupérer les IDs des adhérents affectés pour l'activité récente
        $adherentIds = $this->getAgentAdherentIds();
        
        if ($adherentIds !== null && !empty($adherentIds)) {
            // Activité récente pour les adhérents affectés
            $recentActivity = LogConnexion::with('user.agence')
                ->where(function($query) use ($user, $adherentIds) {
                    $query->where('user_id', $user->id)
                          ->orWhereIn('user_id', function($q) use ($adherentIds) {
                              $q->select('user_id')
                                ->from('adherents')
                                ->whereIn('id', $adherentIds)
                                ->whereNotNull('user_id');
                          });
                })
                ->latest()
                ->take(10)
                ->get();
        } else {
            // Si pas d'adhérents affectés, afficher uniquement l'activité de l'agent
            $recentActivity = LogConnexion::with('user.agence')
                ->where('user_id', $user->id)
                ->latest()
                ->take(10)
                ->get();
        }

        return view('backoffice.dashboard.agent', compact('stats', 'agence', 'recentActivity'));
    }

    /**
     * Dashboard Adhérent
     */
    public function adherentDashboard()
    {
        $user = auth()->user();
        // Eager loading optimisé pour éviter les N+1 dans la vue
        $adherent = $user->adherent()->with([
            'ayantsDroit' => function($query) {
                $query->where('statut_validation', 'en_attente')
                      ->orWhere('statut_validation', 'validé');
            },
            'documents.typeDocument' => function($query) {
                $query->where('actif', true);
            },
            'adhesions.plan',
            'credits' => function($query) {
                $query->whereIn('statut', ['en_attente', 'approuvé'])
                      ->latest()
                      ->limit(3);
            }
        ])->first();

        // Compter uniquement les types de documents obligatoires et actifs
        $requiredTypesCount = \App\Models\TypeDocument::where('actif', true)
            ->where('obligatoire', true)
            ->count();

        // Compter les documents validés de l'adhérent pour les types obligatoires uniquement
        $validatedRequiredCount = 0;
        if ($adherent) {
            // Utiliser les collections chargées pour éviter des requêtes supplémentaires
            $validatedRequiredCount = $adherent->documents
            ->filter(function ($doc) {
                return $doc->statut === 'validé'
                    && $doc->typeDocument
                    && $doc->typeDocument->actif
                    && $doc->typeDocument->obligatoire;
            })
            ->count();
        }

        $stats = [
            'solde_epargne' => 0,
            'prochain_paiement' => null,
            'notifications_non_lues' => 0,
        ];

        // Calcul de la progression du profil
        $completionProfil = 0;
        if ($adherent) {
            $completedFields = 0;
            $totalFields = 6; // Nombre total de champs à vérifier
            
            // Vérification des champs obligatoires
            $fieldsToCheck = [
                'nom', 'prenom', 'email', 'telephone', 'adresse', 'date_naissance'
            ];
            
            foreach ($fieldsToCheck as $field) {
                if (!empty($adherent->$field)) {
                    $completedFields++;
                }
            }
            
            // Ajout de la vérification des documents obligatoires
            $documentsRatio = $requiredTypesCount > 0 
                ? ($validatedRequiredCount / $requiredTypesCount) * 2 // Poids de 2 pour les documents
                : 0;
                
            $completionProfil = min(100, (($completedFields / $totalFields) * 100) + $documentsRatio);
        }

        return view('adherent.dashboard.index', compact(
            'user', 
            'adherent', 
            'stats', 
            'requiredTypesCount', 
            'validatedRequiredCount',
            'completionProfil'
        ));
    }

    /**
     * Statistiques globales pour l'admin (API)
     */
    public function getStats(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'agents' => User::where('role', 'agent')->count(),
                'adherents' => User::where('role', 'adherent')->count(),
            ],
            'agences' => [
                'total' => Agence::count(),
                'active' => Agence::where('active', true)->count(),
            ],
            'adherents' => [
                'total' => Adherent::count(),
                'actifs' => Adherent::where('statut_compte', 'actif')->count(),
                'en_attente' => Adherent::where('statut_compte', 'en_attente_de_verification')->count(),
                'inactifs' => Adherent::where('statut_compte', 'inactif')->count(),
            ],
            'documents' => [
                'total' => Document::count(),
                'soumis' => Document::where('statut', 'soumis')->count(),
                'valides' => Document::where('statut', 'validé')->count(),
                'rejetes' => Document::where('statut', 'rejeté')->count(),
            ],
            'ayants_droit' => [
                'total' => AyantDroit::count(),
                'valides' => AyantDroit::where('statut_validation', 'validé')->count(),
                'en_attente' => AyantDroit::where('statut_validation', 'en_attente')->count(),
                'rejetes' => AyantDroit::where('statut_validation', 'rejeté')->count(),
            ],
            'activity' => [
                'logins_today' => LogConnexion::where('action', 'login')
                                    ->whereDate('created_at', today())
                                    ->count(),
                'failed_logins_today' => LogConnexion::where('action', 'failed_login')
                                            ->whereDate('created_at', today())
                                            ->count(),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Statistiques par agence (pour les chefs de service) ou par agent
     */
    public function getAgenceStats(Request $request, $agenceId = null)
    {
        $user = $request->user();
        
        // Vérifier les permissions
        if ($user->isAdmin()) {
            $agenceId = $agenceId ?: $user->agence_id;
        } elseif ($user->isChefService() || $user->isAgent()) {
            $agenceId = $user->agence_id;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Pour les agents, filtrer par adhérents affectés
        $adherentsQuery = Adherent::query();
        $adherentsQuery = $this->applyAgentFilter($adherentsQuery, null);
        
        // Ajouter le filtre d'agence si nécessaire
        if ($agenceId && !in_array($user->role, ['agent'])) {
            $adherentsQuery->whereHas('user', function($query) use ($agenceId) {
                $query->where('agence_id', $agenceId);
            });
        }

        $stats = [
            'adherents' => [
                'total' => (clone $adherentsQuery)->count(),
                'actifs' => (clone $adherentsQuery)->where('statut_compte', 'actif')->count(),
                'en_attente' => (clone $adherentsQuery)->where('statut_compte', 'en_attente_de_verification')->count(),
            ],
        ];
        
        // Documents filtrés
        $documentsQuery = Document::query();
        $documentsQuery = $this->applyAgentFilter($documentsQuery, 'adherent');
        
        $stats['documents'] = [
            'en_attente' => (clone $documentsQuery)->where('statut', 'soumis')->count(),
            'valides' => (clone $documentsQuery)->where('statut', 'validé')->count(),
        ];
        
        // Ayants droit filtrés
        $ayantsDroitQuery = AyantDroit::query();
        $ayantsDroitQuery = $this->applyAgentFilter($ayantsDroitQuery, 'adherent');
        
        $stats['ayants_droit'] = [
            'en_attente' => (clone $ayantsDroitQuery)->where('statut_validation', 'en_attente')->count(),
            'valides' => (clone $ayantsDroitQuery)->where('statut_validation', 'validé')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Activité récente pour le dashboard
     */
    public function getRecentActivity()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $activity = LogConnexion::with('user')
                ->latest()
                ->take(15)
                ->get();
        } elseif ($user->isAgent()) {
            // Pour les agents, afficher uniquement l'activité de leurs adhérents affectés
            $adherentIds = $this->getAgentAdherentIds();
            
            if ($adherentIds !== null && !empty($adherentIds)) {
                $activity = LogConnexion::with('user')
                    ->where(function($query) use ($user, $adherentIds) {
                        $query->where('user_id', $user->id)
                              ->orWhereIn('user_id', function($q) use ($adherentIds) {
                                  $q->select('user_id')
                                    ->from('adherents')
                                    ->whereIn('id', $adherentIds)
                                    ->whereNotNull('user_id');
                              });
                    })
                    ->latest()
                    ->take(15)
                    ->get();
            } else {
                $activity = LogConnexion::with('user')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->take(15)
                    ->get();
            }
        } elseif ($user->isChefService()) {
            // Pour les chefs de service, afficher l'activité de l'agence
            $activity = LogConnexion::with('user')
                ->where('user_id', $user->id)
                ->orWhereHas('user', function($query) use ($user) {
                    $query->where('agence_id', $user->agence_id)
                          ->where('role', 'adherent');
                })
                ->latest()
                ->take(15)
                ->get();
        } else {
            $activity = LogConnexion::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get();
        }

        return response()->json($activity);
    }

    /**
     * Statistiques pour les graphiques (API)
     */
    public function getChartData(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isAdmin() && !$user->isChefService()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Données pour le graphique des adhérents par mois
        $adherentsParMois = Adherent::select(
            DB::raw('YEAR(created_at) as annee'),
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('annee', 'mois')
        ->orderBy('annee', 'asc')
        ->orderBy('mois', 'asc')
        ->get();

        // Données pour le graphique des statuts de documents
        $statutsDocuments = Document::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();

        // Données pour le graphique des types de bénéficiaires
        $typesBeneficiaires = AyantDroit::select('type_beneficiaire', DB::raw('COUNT(*) as total'))
            ->groupBy('type_beneficiaire')
            ->get();

        return response()->json([
            'adherents_par_mois' => $adherentsParMois,
            'statuts_documents' => $statutsDocuments,
            'types_beneficiaires' => $typesBeneficiaires,
        ]);
    }

    /**
     * Recherche globale dans l'admin
     */
    public function globalSearch(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'adherents' => [],
                'credits' => [],
                'users' => []
            ]);
        }
        
        $user = auth()->user();
        
        // Recherche dans les adhérents
        $adherentsQuery = Adherent::query()
            ->where(function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('prenom', 'like', "%{$query}%")
                  ->orWhere('numero_adherent', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('telephone', 'like', "%{$query}%");
            });
        
        // Filtrer par agent pour les agents, par agence pour les autres non-admins
        $adherentsQuery = $this->applyAgentFilter($adherentsQuery, null);
        
        $adherents = $adherentsQuery->limit(5)->get(['id', 'nom', 'prenom', 'numero_adherent']);
        
        // Recherche dans les crédits
        $creditsQuery = Credit::query()
            ->with('adherent:id,nom,prenom')
            ->where(function($q) use ($query) {
                $q->where('numero_credit', 'like', "%{$query}%")
                  ->orWhereHas('adherent', function($subq) use ($query) {
                      $subq->where('nom', 'like', "%{$query}%")
                           ->orWhere('prenom', 'like', "%{$query}%")
                           ->orWhere('numero_adherent', 'like', "%{$query}%");
                  });
            });
        
        // Filtrer par agent pour les agents
        $creditsQuery = $this->applyAgentFilter($creditsQuery, 'adherent');
        
        $credits = $creditsQuery->limit(5)->get();
        
        // Recherche dans les utilisateurs (admin uniquement)
        $users = [];
        if ($user->isAdmin()) {
            $users = User::query()
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->whereIn('role', ['admin', 'agent', 'chef_service'])
                ->limit(5)
                ->get(['id', 'name', 'email']);
        }
        
        return response()->json([
            'adherents' => $adherents->map(function($adherent) {
                return [
                    'id' => $adherent->id,
                    'nom' => $adherent->nom,
                    'prenom' => $adherent->prenom,
                    'numero_adherent' => $adherent->numero_adherent,
                ];
            }),
            'credits' => $credits->map(function($credit) {
                return [
                    'id' => $credit->id,
                    'numero_credit' => $credit->numero_credit,
                    'adherent_name' => $credit->adherent ? ($credit->adherent->nom . ' ' . $credit->adherent->prenom) : '',
                    'montant' => number_format($credit->montant, 0, ',', ' '),
                ];
            }),
            'users' => collect($users)->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
        ]);
    }
}
