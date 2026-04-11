<?php

namespace App\Domain\ValueObjects;

use Exception;

class UserPassword
{
 private string $hashedValue;

 public function __construct(string $value)
 {
  // The Rule is "Encapsulated" here
  if (strlen($value) < 8) {
   throw new Exception("Password must be at least 8 characters.");
  }

  if (!preg_match('/[0-9]/', $value)) {
   throw new Exception("Password must contain at least one number.");
  }

  $this->hashedValue = password_hash($value, PASSWORD_BCRYPT);
 }

 public function getHash(): string
 {
  return $this->hashedValue;
 }
}
