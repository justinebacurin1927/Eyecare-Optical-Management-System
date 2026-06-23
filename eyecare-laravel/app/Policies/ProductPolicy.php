<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Staff']);
    }

    public function update(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Staff']);
    }

    public function delete(User $user): bool
    {
        return $user->role === 'Admin';
    }
}
