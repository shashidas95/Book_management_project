<?php

namespace App\Infrastructure\Persistence;
use App\Domain\Entities\User as DomainUser;
use App\Domain\Repositories\UserRepositoryInterface;
use Exception;

class InMemoryUserRepository implements UserRepositoryInterface
{

 // Our "Fake" Database table
 private array $users = [];
 private int $nextId = 1;


 public function findByEmail(string $email): ?DomainUser
 {
  foreach ($this->users as $user) {
   if ($user->email === $email) {
    return $user;
   }
  }
  return null;
 }
 public function findById(int $id): DomainUser
 {
  if (!isset($this->users[$id])) {
   throw new Exception("User not found in memory.");
  }
  return $this->users[$id];
 }
 public function save(DomainUser $user)
 {
  // If it's a new user, give them an ID
  if ($user->id === null) {
   $user->id = $this->nextId++;
  }
  // Store the object in our array
  $this->users[$user->id] = $user;
  return $user;
 }
}
