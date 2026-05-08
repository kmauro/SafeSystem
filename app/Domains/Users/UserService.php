<?php

namespace App\Domains\Users;

use App\Domains\Users\User;
use App\Domains\Users\UserRepository;


class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        // You can add additional business logic here, such as sending a welcome email
        return $this->userRepository->create($data);
    }
}
