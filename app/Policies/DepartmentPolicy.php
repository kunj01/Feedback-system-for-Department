<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view departments') || $user->hasAnyRole(['Admin', 'TnP', 'Head']);
    }

    public function view(User $user, Department $department): bool
    {
        return $user->hasPermissionTo('view departments') || $user->hasAnyRole(['Admin', 'TnP', 'Head']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create departments') || $user->hasRole('Admin');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->hasPermissionTo('update departments') || $user->hasRole('Admin');
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermissionTo('delete departments') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return false;
    }
}
