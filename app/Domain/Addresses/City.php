<?php

namespace App\Domain\Addresses;

use App\Domain\ValueObjects\Primitives\Text;

class City extends Text
{
  protected int $maxLen = 100;
}