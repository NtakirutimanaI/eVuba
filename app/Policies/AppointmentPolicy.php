<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any appointments.
     * Only admins/managers could view all; customers cannot.
     */
    public function viewAny(User $user): bool
    {
        // Example: only admins or managers
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can view a specific appointment.
     * Customers can view only their own appointments.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id
            || $user->hasRole('admin')
            || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can create appointments.
     * Any authenticated user can create.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the appointment.
     * Customers can update only their own appointments.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id
            || $user->hasRole('admin')
            || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can delete the appointment.
     * Customers can delete only their own appointments.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id
            || $user->hasRole('admin')
            || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can restore the appointment.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can permanently delete the appointment.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('admin');
    }
}
