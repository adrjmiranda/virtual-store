<?php

namespace App\Domain\Users;

use App\Domain\ValueObjects\Primitives\Text;
use InvalidArgumentException;

class Email extends Text
{
  public function __construct(string $value)
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException("Invalid email address.", 500);
    }

    parent::__construct($value);
  }
}