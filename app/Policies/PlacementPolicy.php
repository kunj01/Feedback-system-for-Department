<?php

namespace App\Policies;

use App\Models\StudentPlacement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlacementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view placements') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide', 'Student']);
    }

    public function view(User $user, StudentPlacement $studentPlacement): bool
    {
        // Students can view their own placements
        if ($user->hasRole('Student')) {
            $student = $user->student;
            if ($student && $studentPlacement->student_id === $student->id) {
                return true;
            }
        }
        return $user->hasPermissionTo('view placements') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create placements') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function update(User $user, Placement $placement): bool
    {
        return $user->hasPermissionTo('edit placements') || $user->hasAnyRole(['Admin', 'TnP']);
    }

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
