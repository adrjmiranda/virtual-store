<?php

namespace App\Domain\ValueObjects\Primitives;

abstract class Boolean
{
  public function __construct(private readonly bool $value)
  {
  }

  public function value(): bool
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return $this->value() === $other->value();
  }
}