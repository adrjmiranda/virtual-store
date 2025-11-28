<?php

namespace App\Domain\ProductOptionValues;

use App\Domain\ValueObjects\Primitives\Text;

class Value extends Text
{
  protected int $maxLen = 100;
}