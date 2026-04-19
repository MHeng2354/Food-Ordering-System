<?php

namespace App\Policies;

use App\Models\Food;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any foods.
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the food.
     */
    public function view(User $user, Food $food)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create foods.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the food.
     */
    public function update(User $user, Food $food)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the food.
     */
    public function delete(User $user, Food $food)
    {
        return $user->role === 'admin';
    }
}
