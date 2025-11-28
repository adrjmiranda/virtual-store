<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Primitives\Text;

class Description extends Text
{
  protected int $maxLen = 1000;
}