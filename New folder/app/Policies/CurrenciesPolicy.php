<?php

namespace App\Policies;

use App\Models\Currencies;
use App\Models\User;

class CurrenciesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Currencies $currencies): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Currencies $currencies): bool
    {
        return $user->role == 0;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Currencies $currencies): bool
    {
        return $user->role == 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Currencies $currencies): bool
    {
        return $user->role == 0;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Currencies $currencies): bool
    {
        return $user->role == 0;
    }
}