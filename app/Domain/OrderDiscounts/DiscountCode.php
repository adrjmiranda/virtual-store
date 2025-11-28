<?php

namespace App\Domain\OrderDiscounts;

use App\Domain\ValueObjects\Primitives\Text;

class DiscountCode extends Text
{
  protected int $maxLen = 50;
}