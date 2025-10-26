<?php

namespace App\Policies;

use App\Models\ConditionEligibiliteCredit;
use App\Models\User;

class ConditionEligibiliteCreditPolicy
{
    public function viewAny(User $user): bool
    {
        // Staff only
        return $user->isAdmin() || $user->isAgent() || $user->isChefService() || $user->isSuperviseur();
    }

    public function view(User $user, ConditionEligibiliteCredit $condition): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        // Restrict creation to admin/chef_service
        return $user->isAdmin() || $user->isChefService();
    }

    public function update(User $user, ConditionEligibiliteCredit $condition): bool
    {
        return $user->isAdmin() || $user->isChefService();
    }

    public function toggleActive(User $user, ConditionEligibiliteCredit $condition): bool
    {
        return $user->isAdmin() || $user->isChefService();
    }
}
