<?php

namespace App\Policies;

use App\Models\CookProcess;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DishPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dish $dish): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       Log::debug("dish policy launched");
       return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dish $dish): bool
    {
        return $user->hasPermissionTo('edit notes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dish $dish): bool
    {
        return $user->hasPermissionTo('delete notes');
    }

}
