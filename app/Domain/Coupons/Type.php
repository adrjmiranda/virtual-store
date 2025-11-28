<?php

namespace App\Domain\Coupons;

use App\Domain\ValueObjects\Enum\CouponType;

class Type
{
  public function __construct(private readonly CouponType $value)
  {
  }

  public function value(): CouponType
  {
    return $this->value;
  }
}