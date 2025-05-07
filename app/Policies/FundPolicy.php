<?php

namespace App\Policies;

use App\Models\Fund;
use App\Models\User;

class FundPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view their funds
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fund $fund): bool
    {
        return $user->id === $fund->user_id; // User can only view their own funds
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create funds
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fund $fund): bool
    {
        return $user->id === $fund->user_id; // User can only update their own funds
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fund $fund): bool
    {
        return $user->id === $fund->user_id; // User can only delete their own funds
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fund $fund): bool
    {
        return $user->id === $fund->user_id; // User can only restore their own funds
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fund $fund): bool
    {
        return $user->id === $fund->user_id; // User can only force delete their own funds
    }
}
