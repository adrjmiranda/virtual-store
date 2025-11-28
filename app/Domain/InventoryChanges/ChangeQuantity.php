<?php

namespace App\Domain\InventoryChanges;

use InvalidArgumentException;

class ChangeQuantity
{
  public function __construct(private readonly int $value)
  {
  }

  public function value(): int
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return $this->value() === $other->value();
  }

  public function isIncrease(): bool
  {
    return $this->value() > 0;
  }

  public function isDecrease(): bool
  {
    return $this->value() < 0;
  }
}
