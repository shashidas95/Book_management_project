<?php
namespace App\Application\UseCases;

use App\Models\User;

class LogoutUser
{
public function execute(User $user): void
{
// This deletes the specific token the user is currently using
// $user->currentAccessToken()->delete();

// OR: If you want to log out from ALL devices:
$user->tokens()->delete();
}
}
