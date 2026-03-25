<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DemandeRetrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandeRetraitPolicy
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
    public function view(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent voir toutes les demandes
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut voir ses propres demandes
        if ($user->isAdherent() && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Admin can create for management
        if ($user->isAdmin()) {
            return true;
        }
        // Seuls les adhérents peuvent créer des demandes de retrait
        return $user->isAdherent() && $user->adherent;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent modifier toutes les demandes
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut modifier ses demandes en attente
        if ($user->isAdherent() && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id && $demandeRetrait->statut === 'en_attente';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent supprimer toutes les demandes
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut supprimer ses demandes en attente
        if ($user->isAdherent() && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id && $demandeRetrait->statut === 'en_attente';
        }

        return false;
    }

    /**
     * Determine whether the user can validate the withdrawal request.
     */
    public function validate(User $user, DemandeRetrait $demandeRetrait)
    {
        return ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur())
            && $demandeRetrait->statut === 'en_attente';
    }

    /**
     * Determine whether the user can reject the withdrawal request.
     */
    public function reject(User $user, DemandeRetrait $demandeRetrait)
    {
        return ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur())
            && $demandeRetrait->statut === 'en_attente';
    }

    /**
     * Determine whether the user can process the withdrawal request.
     */
    public function process(User $user, DemandeRetrait $demandeRetrait)
    {
        return ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur())
            && $demandeRetrait->statut === 'validé';
    }

    /**
     * Determine whether the user can view withdrawal history.
     */
    public function viewHistory(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent voir tous les historiques
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }

        // Adhérent peut voir l'historique de ses demandes
        if ($user->isAdherent() && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id;
        }

        return false;
    }
}
