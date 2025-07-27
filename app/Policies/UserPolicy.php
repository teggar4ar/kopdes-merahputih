<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view all members');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, or admins/supervisors can view any profile
        return $user->id === $model->id || $user->hasPermissionTo('view all members');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage members');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile, or admins can update any profile
        return ($user->id === $model->id && $user->hasPermissionTo('update own profile'))
            || $user->hasPermissionTo('manage members');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('manage members') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('manage members');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('manage members') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can approve member registration.
     */
    public function approve(User $user): bool
    {
        return $user->hasPermissionTo('approve member registration');
    }

    /**
     * Determine whether the user can activate/deactivate member accounts.
     */
    public function toggleStatus(User $user): bool
    {
        return $user->hasPermissionTo('activate member accounts')
            || $user->hasPermissionTo('deactivate member accounts');
    }
}
