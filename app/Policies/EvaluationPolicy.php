<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view evaluations') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide', 'Student']);
    }

    public function view(User $user, Evaluation $evaluation): bool
    {
        // Students can view their own evaluations
        if ($user->hasRole('Student')) {
            $student = $user->student;
            if ($student && $evaluation->student_id === $student->id) {
                return true;
            }
        }
        return $user->hasPermissionTo('view evaluations') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create evaluations') || $user->hasAnyRole(['Admin', 'TnP', 'Guide']);
    }

    public function update(User $user, Evaluation $evaluation): bool
    {
        // Evaluators can update their own evaluations
        if ($evaluation->evaluator_id === $user->id) {
            return true;
        }
        return $user->hasPermissionTo('edit evaluations') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function delete(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('delete evaluations') || $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evaluation $evaluation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evaluation $evaluation): bool
    {
        return false;
    }
}
