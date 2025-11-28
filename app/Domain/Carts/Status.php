<?php

namespace App\Domain\Carts;

use App\Domain\ValueObjects\Enum\CartStatus;

class Status
{
  public function __construct(private readonly CartStatus $value)
  {
  }

  public function value(): CartStatus
  {
    return $this->value;
  }
}