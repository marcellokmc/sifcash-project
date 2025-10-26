<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
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
    public function view(User $user, Document $document): bool
    {
        // L'adhérent peut voir ses propres documents
        if ($user->isAdherent() && $user->adherent && $user->adherent->id === $document->adherent_id) {
            return true;
        }

        // Les agents peuvent voir les documents de leur agence
        if ($user->isAgent() && $user->agence_id) {
            return $document->adherent->user->agence_id === $user->agence_id;
        }

        // Les admins peuvent tout voir
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les adhérents peuvent uploader des documents
        return $user->isAdherent() && $user->adherent;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        // Personne ne peut modifier un document après upload (on crée une nouvelle version)
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        // L'adhérent peut supprimer ses propres documents non validés
        if ($user->isAdherent() && $user->adherent && $user->adherent->id === $document->adherent_id) {
            return $document->isSoumis();
        }

        return false;
    }

    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    /**
     * Determine whether the user can validate the model.
     */
    public function validate(User $user, Document $document): bool
    {
        // Seuls les agents et admins peuvent valider
        return $user->isAgent() || $user->isAdmin();
    }
}