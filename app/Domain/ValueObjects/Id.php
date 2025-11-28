<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Id
{
  public function __construct(private readonly int $value)
  {
    if ($value < 1) {
      throw new InvalidArgumentException("ID cannot be less than 1.", 500);
    }
  }

  public function value(): int
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return $this->value() === $other->value();
  }
}