<?php

declare(strict_types=1);

namespace App\Presentation\Policies;

use App\Infrastructure\Models\UserModel;

class PlanPolicy
{
    public function viewAny(UserModel $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(UserModel $user): bool
    {
        return true;
    }

    public function create(UserModel $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(UserModel $user): bool
    {
        return $user->role === 'admin';
    }

    public function delete(UserModel $user): bool
    {
        return $user->role === 'admin';
    }
}