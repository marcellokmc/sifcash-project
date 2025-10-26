<?php

namespace App\Policies;

use App\Models\Credit;
use App\Models\User;

class CreditPolicy
{
    public function viewAny(User $user): bool
    {
        // Admin/agent/chef/superviseur can view any credits list
        return $user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur();
    }
    
    public function create(User $user): bool
    {
        // Only adherents with an adherent profile can create a credit request
        return $user->isAdherent() && $user->adherent !== null;
    }

    public function view(User $user, Credit $credit): bool
    {
        // Admin/agent/chef can view any; adherent can view own
        if ($user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur()) {
            return true;
        }
        return $user->isAdherent() && $user->adherent && $user->adherent->id === $credit->adherent_id;
    }

    public function approve(User $user, Credit $credit): bool
    {
        // Only staff roles
        return $user->isAdmin() || $user->isAgent() || $user->isChefService();
    }

    public function reject(User $user, Credit $credit): bool
    {
        return $this->approve($user, $credit);
    }

    public function contract(User $user, Credit $credit): bool
    {
        return $this->approve($user, $credit);
    }

    public function generateSchedule(User $user, Credit $credit): bool
    {
        return $this->approve($user, $credit);
    }

    public function recordPayment(User $user, Credit $credit): bool
    {
        return $this->approve($user, $credit);
    }
}
