<?php

namespace App\Policies;

use App\Models\Adhesion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdhesionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Adhesion $adhesion): bool
    {
        // Admin peut tout voir
        if ($user->isAdmin()) {
            return true;
        }

        // Agent/ChefService ne peut voir que les adhésions de son agence
        if ($user->isAgent() || $user->isChefService()) {
            return $adhesion->adherent->user->agence_id === $user->agence_id;
        }

        // Adhérent ne peut voir que ses propres adhésions
        if ($user->isAdherent()) {
            return $adhesion->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin, Agent, ChefService peuvent créer des adhésions
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService()) {
            return true;
        }

        // Adhérent peut créer ses propres adhésions
        if ($user->isAdherent() && $user->adherent) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Adhesion $adhesion): bool
    {
        // Seuls les admin/agent/chefService peuvent modifier
        if (!$user->isAdmin() && !$user->isAgent() && !$user->isChefService()) {
            return false;
        }

        // On ne peut modifier que les adhésions non actives
        if ($adhesion->isActif()) {
            return false;
        }

        // Agent/ChefService ne peut modifier que les adhésions de son agence
        if ($user->isAgent() || $user->isChefService()) {
            return $adhesion->adherent->user->agence_id === $user->agence_id;
        }

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Adhesion $adhesion): bool
    {
        // Seuls les admin peuvent supprimer
        if (!$user->isAdmin()) {
            return false;
        }

        // On ne peut supprimer que les adhésions non actives
        return !$adhesion->isActif();
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function activate(User $user, Adhesion $adhesion): bool
    {
        // Admin, Agent, ChefService peuvent activer
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService()) {
            // Agent/ChefService ne peut activer que les adhésions de son agence
            if ($user->isAgent() || $user->isChefService()) {
                return $adhesion->adherent->user->agence_id === $user->agence_id;
            }
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can close the model.
     */
    public function close(User $user, Adhesion $adhesion): bool
    {
        return $this->activate($user, $adhesion);
    }

    /**
     * Determine whether the user can suspend the model.
     */
    public function suspend(User $user, Adhesion $adhesion): bool
    {
        return $this->activate($user, $adhesion);
    }

    /**
     * Determine whether the user can renew the model.
     */
    public function renew(User $user, Adhesion $adhesion): bool
    {
        return $this->activate($user, $adhesion);
    }

    /**
     * Determine whether the user can view renewals.
     */
    public function viewRenewals(User $user, Adhesion $adhesion): bool
    {
        return $this->view($user, $adhesion);
    }
}