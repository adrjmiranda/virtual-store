<?php

namespace App\Domain\Users;

use App\Domain\ValueObjects\Enum\UserRole;

class Role
{
  public function __construct(private readonly UserRole $value)
  {
  }

  public function value(): UserRole
  {
    return $this->value;
  }
}