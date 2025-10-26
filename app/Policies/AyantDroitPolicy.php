<?php

namespace App\Policies;

use App\Models\AyantDroit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AyantDroitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdherent() || $user->isAgent() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AyantDroit $ayantDroit): bool
    {
        // L'adhérent peut voir ses propres ayants droit
        if ($user->isAdherent() && $user->adherent && $user->adherent->id === $ayantDroit->adherent_id) {
            return true;
        }

        // Les agents peuvent voir les ayants droit de leur agence
        if ($user->isAgent() && $user->agence_id) {
            return $ayantDroit->adherent->user->agence_id === $user->agence_id;
        }

        // Les admins peuvent tout voir
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les adhérents peuvent créer des ayants droit
        return $user->isAdherent() && $user->adherent;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AyantDroit $ayantDroit): bool
    {
        // L'adhérent peut modifier ses propres ayants droit non validés
        if ($user->isAdherent() && $user->adherent && $user->adherent->id === $ayantDroit->adherent_id) {
            return $ayantDroit->isEnAttente();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AyantDroit $ayantDroit): bool
    {
        // L'adhérent peut supprimer ses propres ayants droit non validés
        if ($user->isAdherent() && $user->adherent && $user->adherent->id === $ayantDroit->adherent_id) {
            return $ayantDroit->isEnAttente();
        }

        return false;
    }

    /**
     * Determine whether the user can validate the model.
     */
    public function validate(User $user, AyantDroit $ayantDroit): bool
    {
        // Seuls les agents et admins peuvent valider
        return $user->isAgent() || $user->isAdmin();
    }
}