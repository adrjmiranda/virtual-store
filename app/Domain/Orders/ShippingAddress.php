<?php

namespace App\Domain\Orders;

use App\Domain\ValueObjects\Primitives\Text;

class ShippingAddress extends Text
{
  protected int $maxLen = 500;
}