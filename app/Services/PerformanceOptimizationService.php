<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Adherent;
use App\Models\Document;
use App\Models\Plan;
use App\Models\Adhesion;
use App\Models\Credit;
use App\Models\Epargne;
use Carbon\Carbon;

class PerformanceOptimizationService
{
    /**
     * Durées de cache en minutes
     */
    const CACHE_SHORT = 5; // 5 minutes pour les données fréquemment modifiées
    const CACHE_MEDIUM = 30; // 30 minutes pour les statistiques
    const CACHE_LONG = 1440; // 24 heures pour les données statiques

    /**
     * Cache des statistiques du dashboard admin
     */
    public function getAdminDashboardStats()
    {
        return Cache::remember('admin_dashboard_stats', self::CACHE_MEDIUM, function () {
            return [
                'total_users' => User::count(),
                'total_adherents' => Adherent::count(),
                'adherents_actifs' => Adherent::where('statut_compte', 'actif')->count(),
                'adherents_en_attente' => Adherent::where('statut_compte', 'en_attente_de_verification')->count(),
                'documents_en_attente' => Document::where('statut', 'soumis')->count(),
                'plans_actifs' => Plan::where('actif', true)->count(),
                'adhesions_actives' => Adhesion::where('statut', 'active')->count(),
                'credits_en_attente' => Credit::where('statut', 'en_attente')->count(),
                'total_epargne' => Epargne::where('statut', 'actif')->sum('montant_total'),
                'today_logins' => \App\Models\LogConnexion::where('action', 'login')
                    ->whereDate('created_at', today())
                    ->count(),
            ];
        });
    }

    /**
     * Cache des statistiques adhérent
     */
    public function getAdherentDashboardStats($adherentId)
    {
        return Cache::remember("adherent_dashboard_stats_{$adherentId}", self::CACHE_SHORT, function () use ($adherentId) {
            $adherent = Adherent::with([
                'adhesions.plan',
                'credits' => function ($query) {
                    $query->latest()->limit(5);
                },
                'epargnes' => function ($query) {
                    $query->where('statut', 'actif');
                }
            ])->find($adherentId);

            if (!$adherent) {
                return null;
            }

            return [
                'adhesions_actives' => $adherent->adhesions->where('statut', 'active')->count(),
                'solde_total_epargne' => $adherent->epargnes->sum('montant_total'),
                'credits_en_cours' => $adherent->credits->whereIn('statut', ['approuve', 'en_cours'])->count(),
                'prochaine_echeance' => $this->getProchainePaiement($adherentId),
                'completion_profil' => $this->calculateProfilCompletion($adherent),
            ];
        });
    }

    /**
     * Cache des plans populaires
     */
    public function getPlansPopulaires($limit = 5)
    {
        return Cache::remember('plans_populaires', self::CACHE_LONG, function () use ($limit) {
            return Plan::withCount('adhesions')
                ->where('actif', true)
                ->orderBy('adhesions_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cache des statistiques financières globales
     */
    public function getStatistiquesFinancieres()
    {
        return Cache::remember('statistiques_financieres', self::CACHE_MEDIUM, function () {
            return [
                'total_epargne' => Epargne::where('statut', 'actif')->sum('montant_total'),
                'total_credits_actifs' => Credit::whereIn('statut', ['approuve', 'en_cours'])->sum('montant'),
                'total_interets_verses' => Epargne::sum('interets_accumules'),
                'total_frais_percus' => $this->calculateTotalFrais(),
                'evolution_epargne' => $this->getEvolutionEpargne(),
                'evolution_credits' => $this->getEvolutionCredits(),
            ];
        });
    }

    /**
     * Optimisation des requêtes pour la liste des adhérents
     */
    public function getAdherentsOptimized($filters = [])
    {
        $cacheKey = 'adherents_list_' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, self::CACHE_SHORT, function () use ($filters) {
            $query = Adherent::with([
                'user:id,name,email,phone,active',
                'adhesions:id,adherent_id,plan_id,statut,montant,date_debut',
                'adhesions.plan:id,nom,type_plan',
                'documents:id,adherent_id,statut',
            ]);

            // Appliquer les filtres
            if (isset($filters['statut'])) {
                $query->where('statut_compte', $filters['statut']);
            }

            if (isset($filters['agence_id'])) {
                $query->whereHas('user', function ($q) use ($filters) {
                    $q->where('agence_id', $filters['agence_id']);
                });
            }

            if (isset($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
                      });
                });
            }

            return $query->paginate(20);
        });
    }

    /**
     * Cache des données utilisateur fréquemment consultées
     */
    public function getUserData($userId)
    {
        return Cache::remember("user_data_{$userId}", self::CACHE_MEDIUM, function () use ($userId) {
            return User::with([
                'adherent.adhesions.plan',
                'adherent.epargnes',
                'adherent.credits' => function ($query) {
                    $query->latest()->limit(5);
                }
            ])->find($userId);
        });
    }

    /**
     * Invalider le cache pour un utilisateur spécifique
     */
    public function invalidateUserCache($userId)
    {
        $keys = [
            "user_data_{$userId}",
            "adherent_dashboard_stats_{$userId}",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Invalider le cache global
     */
    public function invalidateGlobalCache()
    {
        $keys = [
            'admin_dashboard_stats',
            'statistiques_financieres',
            'plans_populaires'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Calculer la prochaine échéance de paiement
     */
    private function getProchainePaiement($adherentId)
    {
        return Cache::remember("prochaine_echeance_{$adherentId}", self::CACHE_SHORT, function () use ($adherentId) {
            // Logique pour calculer la prochaine échéance
            $adhesions = Adhesion::where('adherent_id', $adherentId)
                ->where('statut', 'active')
                ->with('plan')
                ->get();

            $prochainePaiement = null;
            foreach ($adhesions as $adhesion) {
                $periodicite = $adhesion->plan->periodicite;
                $dateDebut = Carbon::parse($adhesion->date_debut);
                
                // Calculer la prochaine date selon la périodicité
                $prochaineDateCalculee = $this->calculateNextPaymentDate($dateDebut, $periodicite);
                
                if (!$prochainePaiement || $prochaineDateCalculee < $prochainePaiement) {
                    $prochainePaiement = $prochaineDateCalculee;
                }
            }

            return $prochainePaiement;
        });
    }

    /**
     * Calculer le pourcentage de completion du profil
     */
    private function calculateProfilCompletion($adherent)
    {
        $totalFields = 10; // Nombre total de champs à vérifier
        $completedFields = 0;

        // Vérifier les champs obligatoires
        $fieldsToCheck = [
            'nom', 'prenom', 'email', 'telephone', 'adresse', 
            'date_naissance', 'lieu_naissance', 'profession', 
            'numero_piece_identite', 'type_piece_identite'
        ];

        foreach ($fieldsToCheck as $field) {
            if (!empty($adherent->$field)) {
                $completedFields++;
            }
        }

        return round(($completedFields / $totalFields) * 100);
    }

    /**
     * Calculer le total des frais perçus
     */
    private function calculateTotalFrais()
    {
        // Logique pour calculer les frais d'adhésion, de retrait, etc.
        $fraisAdhesion = Adhesion::sum('frais_adhesion_paye') ?? 0;
        $fraisRetrait = \App\Models\DemandeRetrait::where('statut', 'traite')->sum('frais') ?? 0;
        
        return $fraisAdhesion + $fraisRetrait;
    }

    /**
     * Obtenir l'évolution de l'épargne sur les 12 derniers mois
     */
    private function getEvolutionEpargne()
    {
        return DB::table('epargnes')
            ->select(
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('SUM(montant_total) as total')
            )
            ->where('statut', 'actif')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::createFromDate($item->annee, $item->mois, 1)->format('Y-m'),
                    'total' => (float) $item->total
                ];
            });
    }

    /**
     * Obtenir l'évolution des crédits sur les 12 derniers mois
     */
    private function getEvolutionCredits()
    {
        return DB::table('credits')
            ->select(
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('SUM(montant) as total'),
                DB::raw('COUNT(*) as nombre')
            )
            ->whereIn('statut', ['approuve', 'en_cours', 'rembourse'])
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::createFromDate($item->annee, $item->mois, 1)->format('Y-m'),
                    'montant' => (float) $item->total,
                    'nombre' => (int) $item->nombre
                ];
            });
    }

    /**
     * Calculer la prochaine date de paiement selon la périodicité
     */
    private function calculateNextPaymentDate($dateDebut, $periodicite)
    {
        $now = Carbon::now();
        $debut = Carbon::parse($dateDebut);
        
        switch ($periodicite) {
            case 'mensuel':
                while ($debut <= $now) {
                    $debut->addMonth();
                }
                return $debut;
                
            case 'trimestriel':
                while ($debut <= $now) {
                    $debut->addMonths(3);
                }
                return $debut;
                
            case 'semestriel':
                while ($debut <= $now) {
                    $debut->addMonths(6);
                }
                return $debut;
                
            case 'annuel':
                while ($debut <= $now) {
                    $debut->addYear();
                }
                return $debut;
                
            case 'hebdomadaire':
                while ($debut <= $now) {
                    $debut->addWeek();
                }
                return $debut;
                
            default:
                return $debut->addMonth();
        }
    }

    /**
     * Optimiser les requêtes lourdes avec des index
     */
    public function optimizeDatabaseQueries()
    {
        // Suggestions d'index pour améliorer les performances
        $indexSuggestions = [
            'users' => ['email', 'role', 'active', 'agence_id'],
            'adherents' => ['user_id', 'statut_compte', 'created_at'],
            'documents' => ['adherent_id', 'statut', 'type_document_id'],
            'adhesions' => ['adherent_id', 'plan_id', 'statut', 'date_debut'],
            'credits' => ['adherent_id', 'statut', 'created_at'],
            'epargnes' => ['adherent_id', 'statut', 'created_at'],
            'log_connexions' => ['user_id', 'action', 'created_at'],
        ];

        return $indexSuggestions;
    }

    /**
     * Nettoyer le cache expiré
     */
    public function clearExpiredCache()
    {
        // Nettoyer les anciennes entrées de cache
        Cache::flush(); // En production, utiliser des patterns plus spécifiques
        
        return true;
    }

    /**
     * Mesurer les performances d'une requête
     */
    public function measureQueryPerformance(callable $callback)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $result = $callback();
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        return [
            'result' => $result,
            'execution_time' => round(($endTime - $startTime) * 1000, 2), // en millisecondes
            'memory_usage' => round(($endMemory - $startMemory) / 1024 / 1024, 2), // en MB
            'peak_memory' => round(memory_get_peak_usage() / 1024 / 1024, 2) // en MB
        ];
    }
}