<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Primitives\Text;

class IpAddress extends Text
{
  protected int $maxLen = 45;
}