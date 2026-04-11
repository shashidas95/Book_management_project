<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UserPassword;

class User
{
 public function __construct(
  public ?int $id,
  public string $email,
  private string $hashedPassword
 ) {}

 public function updatePassword(UserPassword $newPassword)
 {
  $this->hashedPassword = $newPassword->getHash();
 }

 // Change this name to getPasswordHash to match your test script
 public function getPasswordHash()
 {
  return $this->hashedPassword;
 }
 // Add this method to your existing User class
 public function passwordMatches(string $rawPassword): bool
 {
  return password_verify($rawPassword, $this->hashedPassword);
 }
}
