<?php

namespace App\Domains\Users;

use App\Domains\Roles\Role;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function find(int $userId): ?User
    {
        return User::find($userId);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function all(): array
    {
        return User::with('role')->get()
        ->toArray();
    }

    public function getAllRoles(): array
    {
        return Role::all()->toArray();
    }

    public function update(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user;
    }
}
