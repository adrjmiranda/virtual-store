<?php

namespace App\Domain\Addresses;

use App\Domain\ValueObjects\Primitives\Text;

class State extends Text
{
  protected int $maxLen = 100;
}