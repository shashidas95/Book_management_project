<?php

namespace App\Application\UseCases;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\UserPassword;
use App\Infrastructure\Events\SimpleEventDispatcher;
use App\Infrastructure\Events\UserRegistered;

class RegisterUser
{
public function __construct(
        private UserRepositoryInterface $repository,
        // ADD THE TYPE-HINT HERE
        private SimpleEventDispatcher $dispatcher
    ) {}

 public function execute(string $email, string $rawPassword): User
 {
  // Validation happens AUTOMATICALLY when we create the Value Object
  $password = new UserPassword($rawPassword);
  $user = new User(null, $email, $password->getHash());

$savedUser = $this->repository->save($user);

        // TRIGGER THE EVENT
        $this->dispatcher->dispatch(new UserRegistered($savedUser));

        return $savedUser;

 }
}
