<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\CookProcess;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CookProcessPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CookProcess $cookProcess): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CookProcess $cookProcess): bool
    {
        return $user->hasPermissionTo('edit notes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CookProcess $cookProcess): bool
    {
        return $user->hasPermissionTo('delete notes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CookProcess $cookProcess): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CookProcess $cookProcess): bool
    {
        //
    }
}
