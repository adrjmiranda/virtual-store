<?php

namespace App\Domain\Payments;

use App\Domain\ValueObjects\Primitives\Text;

class Method extends Text
{
  protected int $maxLen = 50;
}