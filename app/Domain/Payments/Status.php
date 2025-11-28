<?php

namespace App\Domain\Payments;

use App\Domain\ValueObjects\Enum\PaymentStatus;

class Status
{
  public function __construct(private readonly PaymentStatus $value)
  {
  }

  public function value(): PaymentStatus
  {
    return $this->value;
  }
}