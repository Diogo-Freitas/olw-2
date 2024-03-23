<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seller;
use App\Enums\RoleEnum;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role_id === RoleEnum::MANAGER || $user->role_id === RoleEnum::SELLER;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Seller $seller): bool
    {
        return $user->role_id === RoleEnum::MANAGER || $user->role_id === RoleEnum::SELLER;
    }

    /**
     * Determine whether the user can create or update models.
     */
    public function createOrUpdate(User $user): bool
    {
        return $user->role_id === RoleEnum::MANAGER || $user->role_id === RoleEnum::SELLER;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->role_id === RoleEnum::MANAGER || $user->role_id === RoleEnum::SELLER;
    }
}
