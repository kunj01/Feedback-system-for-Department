<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view projects
    }

    public function view(User $user, Project $project): bool
    {
        return true; // All authenticated users can view project details
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create projects') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function update(User $user, Project $project): bool
    {
        // Guide can update their own projects
        if ($user->hasRole('Guide') && $project->guide_id === $user->id) {
            return true;
        }
        return $user->hasPermissionTo('update projects') || $user->hasAnyRole(['Admin', 'TnP', 'Head']);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('delete projects') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
