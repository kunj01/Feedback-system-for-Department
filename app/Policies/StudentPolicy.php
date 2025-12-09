<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view students') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function view(User $user, Student $student): bool
    {
        // Students can view their own record
        if ($user->hasRole('Student') && $student->user_id === $user->id) {
            return true;
        }
        return $user->hasPermissionTo('view students') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create students') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function update(User $user, Student $student): bool
    {
        // Students can update their own record (limited fields)
        if ($user->hasRole('Student') && $student->user_id === $user->id) {
            return true;
        }
        return $user->hasPermissionTo('edit students') || $user->hasAnyRole(['Admin', 'TnP', 'Head']);
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('delete students') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return false;
    }
}
