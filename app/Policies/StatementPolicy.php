<?php

namespace App\Policies;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read statements');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Statement $statement): bool
    {
        return $user->can('read statements');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('write statements');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Statement $statement): bool
    {
        return $user->can('write statements');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Statement $statement): bool
    {
        return $user->can('write statements');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Statement $statement): bool
    {
        return $user->can('write statements');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Statement $statement): bool
    {
        return $user->can('write statements');
    }
}
