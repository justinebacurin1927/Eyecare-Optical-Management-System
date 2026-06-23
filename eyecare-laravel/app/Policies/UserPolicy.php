<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function view(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function update(User $user): bool
    {
        return $user->role === 'Admin';
    }

    public function delete(User $user, User $target): bool
    {
        if ($target->role === 'Admin' && User::where('role', 'Admin')->count() <= 1) {
            return false;
        }

        return $user->role === 'Admin' && $user->id !== $target->id;
    }

    public function toggleStatus(User $user, User $target): bool
    {
        return $user->role === 'Admin' && $user->id !== $target->id;
    }
}
