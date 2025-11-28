<?php

namespace App\Domain\Addresses;

use App\Domain\ValueObjects\Primitives\Text;

class Number extends Text
{
  protected int $maxLen = 50;
}