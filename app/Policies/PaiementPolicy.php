<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Paiement;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paiement $paiement)
    {
        // Admin/Agent peuvent voir tous les paiements
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut voir ses propres paiements
        if ($user->isAdherent() && $user->adherent) {
            return $paiement->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Admin can create payments for management
        if ($user->isAdmin()) {
            return true;
        }
        // Seuls les adhérents peuvent créer des paiements
        return $user->isAdherent() && $user->adherent;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paiement $paiement)
    {
        // Admin/Agent peuvent modifier tous les paiements
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut modifier ses paiements en brouillon
        if ($user->isAdherent() && $user->adherent) {
            return $paiement->adherent_id === $user->adherent->id && $paiement->statut === 'brouillon';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paiement $paiement)
    {
        // Admin/Agent peuvent supprimer tous les paiements
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut supprimer ses paiements en brouillon
        if ($user->isAdherent() && $user->adherent) {
            return $paiement->adherent_id === $user->adherent->id && $paiement->statut === 'brouillon';
        }

        return false;
    }

    /**
     * Determine whether the user can validate the payment.
     */
    public function validate(User $user, Paiement $paiement)
    {
        return ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur())
            && in_array($paiement->statut, ['soumis', 'en_attente']);
    }

    /**
     * Determine whether the user can reject the payment.
     */
    public function reject(User $user, Paiement $paiement)
    {
        return ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur())
            && in_array($paiement->statut, ['soumis', 'en_attente']);
    }

    /**
     * Determine whether the user can download the payment proof.
     */
    public function download(User $user, Paiement $paiement)
    {
        // Admin/Agent peuvent télécharger toutes les preuves
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut télécharger ses propres preuves
        if ($user->isAdherent() && $user->adherent) {
            return $paiement->adherent_id === $user->adherent->id;
        }

        return false;
    }
}
