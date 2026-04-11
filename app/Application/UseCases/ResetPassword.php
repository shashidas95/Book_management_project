<?php

namespace App\Application\UseCases;


use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\UserPassword;
use Illuminate\Support\Facades\DB;


class ResetPassword
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(string $token, string $email,  string $newPassword)
    {
        // 1. Verify the token exists and is not expired (e.g., 60 minutes)
        $reset = DB::table('password_reset_tokens')->where('email', $email)->where('token', $token)->first();
        if (!$reset || now()->subMinutes(60)->gt($reset->created_at)) {
            throw new \Exception("Invalid or expired reset token.");
        }
        // 2. Proceed with user lookup and password update
        $user = $this->repository->findById($email);
        // Validation happens AUTOMATICALLY when we create the Value Object
        // Same rules apply here as in Registration!
        $password = new UserPassword($newPassword);
        $user->updatePassword($password);
        $this->repository->save($user);
        // 3. Clean up: Delete the used token
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}
