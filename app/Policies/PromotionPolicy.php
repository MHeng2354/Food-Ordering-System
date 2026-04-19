<?php

namespace App\Policies;

use App\Models\Promotion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PromotionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any promotions.
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the promotion.
     */
    public function view(User $user, Promotion $promotion)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create promotions.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the promotion.
     */
    public function update(User $user, Promotion $promotion)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the promotion.
     */
    public function delete(User $user, Promotion $promotion)
    {
        return $user->role === 'admin';
    }
}
