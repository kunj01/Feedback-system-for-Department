<?php

namespace App\Policies;

use App\Models\StudentPlacement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPlacementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view placements') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentPlacement $studentPlacement): bool
    {
        return $user->hasPermissionTo('view placements') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create placements') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentPlacement $studentPlacement): bool
    {
        return $user->hasPermissionTo('edit placements') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentPlacement $studentPlacement): bool
    {
        return $user->hasPermissionTo('delete placements') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentPlacement $studentPlacement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentPlacement $studentPlacement): bool
    {
        return false;
    }
}
