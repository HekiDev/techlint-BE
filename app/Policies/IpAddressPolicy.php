<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\IpAddress;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IpAddressPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IpAddress $ipAddress): Response
    {
        if ($user->role === RoleEnum::SUPER_ADMIN->value) {
            return Response::allow();
        }

        return $ipAddress->user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to update record you did not create.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IpAddress $ipAddress): Response
    {
        return $user->role === RoleEnum::SUPER_ADMIN->value
            ? Response::allow()
            : Response::deny('You are not allowed to delete records.');
    }
}
