<?php

namespace App\Policies;

use App\Models\EcheanceCredit;
use App\Models\User;

class EcheanceCreditPolicy
{
    public function view(User $user, EcheanceCredit $echeance): bool
    {
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }
        return $user->isAdherent() && $user->adherent && $user->adherent->id === $echeance->credit->adherent_id;
    }

    public function update(User $user, EcheanceCredit $echeance): bool
    {
        // Only staff roles can update status/date
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }
}
