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

class DashboardController extends Controller
{
    /**
     * Dashboard Administrateur
     */
    public function adminDashboard()
    {
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

        // Récupérer les adhérents de l'agence de l'agent avec optimisation
        $adherentsAgence = Adherent::with('user')
            ->whereHas('user', function($query) use ($user) {
                $query->where('agence_id', $user->agence_id);
            });

        $stats = [
            'total_adherents' => $adherentsAgence->count(),
            'adherents_actifs' => $adherentsAgence->where('statut_compte', 'actif')->count(),
            'adherents_en_attente' => $adherentsAgence->where('statut_compte', 'en_attente_de_verification')->count(),
            'documents_en_attente' => Document::whereHas('adherent.user', function($query) use ($user) {
                $query->where('agence_id', $user->agence_id);
            })->where('statut', 'soumis')->count(),
            'ayants_droit_en_attente' => AyantDroit::whereHas('adherent.user', function($query) use ($user) {
                $query->where('agence_id', $user->agence_id);
            })->where('statut_validation', 'en_attente')->count(),
            'recent_activity' => LogConnexion::where('user_id', $user->id)
                                    ->whereDate('created_at', today())
                                    ->count(),
        ];

        // Activité récente pour l'agent (connexions et actions sur ses adhérents)
        $recentActivity = LogConnexion::with('user.agence')
            ->where('user_id', $user->id)
            ->orWhereHas('user', function($query) use ($user) {
                $query->where('agence_id', $user->agence_id)
                      ->where('role', 'adherent');
            })
            ->latest()
            ->take(10)
            ->get();

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
     * Statistiques par agence (pour les chefs de service)
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

        $stats = [
            'adherents' => [
                'total' => Adherent::whereHas('user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->count(),
                'actifs' => Adherent::whereHas('user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut_compte', 'actif')->count(),
                'en_attente' => Adherent::whereHas('user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut_compte', 'en_attente_de_verification')->count(),
            ],
            'documents' => [
                'en_attente' => Document::whereHas('adherent.user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut', 'soumis')->count(),
                'valides' => Document::whereHas('adherent.user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut', 'validé')->count(),
            ],
            'ayants_droit' => [
                'en_attente' => AyantDroit::whereHas('adherent.user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut_validation', 'en_attente')->count(),
                'valides' => AyantDroit::whereHas('adherent.user', function($query) use ($agenceId) {
                    $query->where('agence_id', $agenceId);
                })->where('statut_validation', 'validé')->count(),
            ]
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
        } elseif ($user->isAgent() || $user->isChefService()) {
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
        
        // Filtrer par agence si l'utilisateur n'est pas admin
        if (!$user->isAdmin()) {
            $adherentsQuery->whereHas('user', function($q) use ($user) {
                $q->where('agence_id', $user->agence_id);
            });
        }
        
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
        
        // Filtrer par agence si nécessaire
        if (!$user->isAdmin()) {
            $creditsQuery->whereHas('adherent.user', function($q) use ($user) {
                $q->where('agence_id', $user->agence_id);
            });
        }
        
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
