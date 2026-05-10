<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function authenticate(string $email, string $password, bool $remember = false): bool
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ], $remember);
    }

    public function updateProfile(int $userId, array $data): User
    {
        return $this->userRepository->update($userId, $data);
    }

    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userRepository->find($userId);

        if (! Auth::validate(['email' => $user->email, 'password' => $currentPassword])) {
            return false;
        }

        $this->userRepository->update($userId, ['password' => bcrypt($newPassword)]);

        return true;
    }
}
