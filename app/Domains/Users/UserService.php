<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    /**
     * @param  array{name: string, email: string, password: string, surname: string, username: string, phone: string}  $data
     */
    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function authenticate(string $username, string $password, bool $remember = false): bool
    {
        return Auth::attempt([
            'username' => $username,
            'password' => $password,
        ], $remember);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->all();
    }

    public function getAllRoles(): array
    {
        return $this->userRepository->getAllRoles();
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function updateProfile(int $userId, array $data): User
    {
        return $this->userRepository->update($userId, $data);
    }

    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userRepository->find($userId);

        if (! Auth::validate(['username' => $user->username, 'password' => $currentPassword])) {
            return false;
        }

        $this->userRepository->update($userId, ['password' => bcrypt($newPassword)]);

        return true;
    }
}
