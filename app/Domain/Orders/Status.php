<?php

namespace App\Domain\Orders;

use App\Domain\ValueObjects\Enum\OrderStatus;

class Status
{
  public function __construct(private readonly OrderStatus $value)
  {
  }

  public function value(): OrderStatus
  {
    return $this->value;
  }
}