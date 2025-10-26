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
        return $user->hasRole(['admin', 'agent', 'chef_service', 'superviseur']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent voir toutes les demandes
        if ($user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])) {
            return true;
        }

        // Adhérent peut voir ses propres demandes
        if ($user->hasRole('adherent') && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Seuls les adhérents peuvent créer des demandes de retrait
        return $user->hasRole('adherent') && $user->adherent;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent modifier toutes les demandes
        if ($user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])) {
            return true;
        }

        // Adhérent peut modifier ses demandes en attente
        if ($user->hasRole('adherent') && $user->adherent) {
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
        if ($user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])) {
            return true;
        }

        // Adhérent peut supprimer ses demandes en attente
        if ($user->hasRole('adherent') && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id && $demandeRetrait->statut === 'en_attente';
        }

        return false;
    }

    /**
     * Determine whether the user can validate the withdrawal request.
     */
    public function validate(User $user, DemandeRetrait $demandeRetrait)
    {
        return $user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])
            && $demandeRetrait->statut === 'en_attente';
    }

    /**
     * Determine whether the user can reject the withdrawal request.
     */
    public function reject(User $user, DemandeRetrait $demandeRetrait)
    {
        return $user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])
            && $demandeRetrait->statut === 'en_attente';
    }

    /**
     * Determine whether the user can process the withdrawal request.
     */
    public function process(User $user, DemandeRetrait $demandeRetrait)
    {
        return $user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])
            && $demandeRetrait->statut === 'validé';
    }

    /**
     * Determine whether the user can view withdrawal history.
     */
    public function viewHistory(User $user, DemandeRetrait $demandeRetrait)
    {
        // Admin/Agent peuvent voir tous les historiques
        if ($user->hasRole(['admin', 'agent', 'chef_service', 'superviseur'])) {
            return true;
        }

        // Adhérent peut voir l'historique de ses demandes
        if ($user->hasRole('adherent') && $user->adherent) {
            return $demandeRetrait->adherent_id === $user->adherent->id;
        }

        return false;
    }
}
