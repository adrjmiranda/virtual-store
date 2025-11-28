<?php

namespace App\Domain\ValueObjects\Enum;

enum CouponType: string
{
  case PERCENT = 'percent';
  case FIXED = 'fixed';
}