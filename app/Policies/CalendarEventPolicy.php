<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CalendarEvent;

class CalendarEventPolicy
{
    /**
     * Admin bypass
     */
    public function before(User $user)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Vendor list
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'vendor';
    }

    /**
     * View single event
     */
    public function view(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->role === 'vendor' && $calendarEvent->vendor_id === $user->id;
    }

    /**
     * Create
     */
    public function create(User $user): bool
    {
        return $user->role === 'vendor';
    }

    /**
     * Update
     */
    public function update(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->role === 'vendor' && $calendarEvent->vendor_id === $user->id;
    }

    /**
     * Delete
     */
    public function delete(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->role === 'vendor' && $calendarEvent->vendor_id === $user->id;
    }
}