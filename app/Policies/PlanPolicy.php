<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir les plans
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Plan $plan): bool
    {
        // Les adhérents ne peuvent voir que les plans actifs
        if ($user->isAdherent()) {
            return $plan->actif;
        }

        // Admin, Agent, ChefService peuvent voir tous les plans
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les admin peuvent créer des plans
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plan $plan): bool
    {
        // Seuls les admin peuvent modifier des plans
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plan $plan): bool
    {
        // Seuls les admin peuvent supprimer des plans
        // Et seulement si le plan n'a pas d'adhésions actives
        if (!$user->isAdmin()) {
            return false;
        }

        return !$plan->adhesions()->where('statut', 'actif')->exists();
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function activate(User $user, Plan $plan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function deactivate(User $user, Plan $plan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can subscribe to the plan.
     */
    public function subscribe(User $user, Plan $plan): bool
    {
        // Seuls les adhérents avec profil complet peuvent souscrire
        if (!$user->isAdherent() || !$user->adherent) {
            return false;
        }

        // Le plan doit être actif
        if (!$plan->actif) {
            return false;
        }

        // L'adhérent doit être actif
        if (!$user->adherent->isActif()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can view plan statistics.
     */
    public function viewStatistics(User $user, Plan $plan): bool
    {
        return $user->isAdmin() || $user->isChefService();
    }
}