<?php

namespace App\Policies;

use App\Models\ReportLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view reports') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide', 'Student']);
    }

    public function view(User $user, ReportLog $reportLog): bool
    {
        // Students can view their own reports
        if ($user->hasRole('Student')) {
            $student = $user->student;
            if ($student && $reportLog->student_id === $student->id) {
                return true;
            }
        }
        // Guides can view reports of their projects
        if ($user->hasRole('Guide') && $reportLog->project) {
            if ($reportLog->project->guide_id === $user->id) {
                return true;
            }
        }
        return $user->hasPermissionTo('view reports') || $user->hasAnyRole(['Admin', 'TnP', 'Head']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create reports') || $user->hasAnyRole(['Admin', 'TnP', 'Guide', 'Student']);
    }

    public function update(User $user, ReportLog $reportLog): bool
    {
        // Students can update their own reports if not yet reviewed
        if ($user->hasRole('Student') && $reportLog->status === 'SUBMITTED') {
            $student = $user->student;
            if ($student && $reportLog->student_id === $student->id) {
                return true;
            }
        }
        return $user->hasPermissionTo('update reports') || $user->hasAnyRole(['Admin', 'TnP', 'Head', 'Guide']);
    }

    public function delete(User $user, ReportLog $reportLog): bool
    {
        // Students can delete their own unreviewed reports
        if ($user->hasRole('Student') && $reportLog->status === 'SUBMITTED') {
            $student = $user->student;
            if ($student && $reportLog->student_id === $student->id) {
                return true;
            }
        }
        return $user->hasPermissionTo('delete reports') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReportLog $reportLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReportLog $reportLog): bool
    {
        return false;
    }
}
