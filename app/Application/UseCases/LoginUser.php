<?php

namespace App\Application\UseCases;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use Exception;


class LoginUser
{
 public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(string $email, string $rawPassword): User
    {
        $user = $this->repository->findByEmail($email);

        // This will result in true or false in your logs
        $check = password_verify($rawPassword, $user->getPasswordHash());

        \Illuminate\Support\Facades\Log::info("Verification Test", [
            'raw_provided' => $rawPassword,
            'hash_in_entity' => $user->getPasswordHash(),
            'does_it_match' => $check
        ]);

        if (!$check) {
            throw new Exception("Invalid credentials.");
        }

        return $user;
    }
// public function execute(string $email, string $rawPassword): User
//     {
//         $user = $this->repository->findByEmail($email);

//         if (!$user) {
//             \Illuminate\Support\Facades\Log::error("Login Fail: Email $email not found.");
//             throw new Exception("Invalid credentials.");
//         }

//         // This will tell us if the Entity actually has the hash from the DB
//         \Illuminate\Support\Facades\Log::info("Login Debug", [
//             'email' => $user->email,
//             'hash_exists' => !empty($user->getPasswordHash()),
//             'hash_prefix' => substr($user->getPasswordHash(), 0, 4) // Should be $2y$
//         ]);

//         if (!$user->passwordMatches($rawPassword)) {
//             throw new Exception("Invalid credentials.");
//         }

//         return $user;
//     }
}
