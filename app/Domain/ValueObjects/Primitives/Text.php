<?php

namespace App\Domain\ValueObjects\Primitives;

use InvalidArgumentException;

abstract class Text
{
  protected int $minLen = 0;
  protected int $maxLen = 255;

  public function __construct(private readonly string $value)
  {
    if (empty($value)) {
      throw new InvalidArgumentException("The text cannot be empty.", 500);
    }

    if (\strlen($value) > $this->minLen) {
      throw new InvalidArgumentException("The text cannot be less than {$this->minLen} characters.", 500);
    }

    if (\strlen($value) > $this->maxLen) {
      throw new InvalidArgumentException("The text cannot exceed {$this->maxLen} characters.", 500);
    }
  }

  public function value(): string
  {
    return $this->value();
  }

  public function equals(self $other): bool
  {
    return $this->value() === $other->value();
  }
}