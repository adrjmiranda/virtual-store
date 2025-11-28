<?php

namespace App\Domain\Coupons;

use App\Domain\ValueObjects\Primitives\Text;

class Code extends Text
{
  protected int $maxLen = 50;
}