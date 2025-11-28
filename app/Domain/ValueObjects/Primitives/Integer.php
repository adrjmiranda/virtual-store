<?php

namespace App\Domain\ValueObjects\Primitives;

use InvalidArgumentException;

abstract class Integer
{
  protected int $min = 0;

  public function __construct(private readonly int $value)
  {
    if ($value < 0) {
      throw new InvalidArgumentException("The value cannot be less than {$this->min}.", 500);
    }
  }
}