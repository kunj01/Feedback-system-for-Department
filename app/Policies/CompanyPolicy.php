<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view companies') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function view(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('view companies') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create companies') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('edit companies') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('delete companies') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}
