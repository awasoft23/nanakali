<?php

namespace App\Policies;

use App\Models\PurchasingInvoice;
use App\Models\User;

class PurchasingInvoicePolicy
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
    public function view(User $user, PurchasingInvoice $purchasingInvoice): bool
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
    public function update(User $user, PurchasingInvoice $purchasingInvoice): bool
    {
        return $purchasingInvoice->paymented == 0 && $user->role == 0;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchasingInvoice $purchasingInvoice): bool
    {
        return $purchasingInvoice->paymented == 0 && $user->role == 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchasingInvoice $purchasingInvoice): bool
    {
        return $purchasingInvoice->paymented == 0 && $user->role == 0;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchasingInvoice $purchasingInvoice): bool
    {
        return $purchasingInvoice->paymented == 0 && $user->role == 0;

    }
}