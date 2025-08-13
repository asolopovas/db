<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function is_admin(User $user)
    {
        return $user->role->name === 'admin';
    }
}
