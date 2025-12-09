<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their notifications
    }

    public function view(User $user, Notification $notification): bool
    {
        // Users can only view their own notifications
        return $notification->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Only admins and TnP can create notifications for others
        return $user->hasPermissionTo('create notifications') || $user->hasAnyRole(['Admin', 'TnP']);
    }

    public function update(User $user, Notification $notification): bool
    {
        // Users can update (mark as read) their own notifications
        return $notification->user_id === $user->id;
    }

    public function delete(User $user, Notification $notification): bool
    {
        // Users can delete their own notifications
        return $notification->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Notification $notification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Notification $notification): bool
    {
        return false;
    }
}
