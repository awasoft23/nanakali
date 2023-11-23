<?php

namespace App\Policies;

use App\Models\Reciepts;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecieptsPolicy
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
    public function view(User $user, Reciepts $reciepts): bool
    {
        return $reciepts->id > 6;
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
    public function update(User $user, Reciepts $reciepts): bool
    {
        return $reciepts->id > 6 && $user->role == 0;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reciepts $reciepts): bool
    {
        return $reciepts->id > 6 && $user->role == 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reciepts $reciepts): bool
    {
        return $reciepts->id > 6 && $user->role == 0;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reciepts $reciepts): bool
    {
        return $reciepts->id > 6 && $user->role == 0;
    }
}