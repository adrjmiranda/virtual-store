<?php

namespace App\Domain\Users;

use App\Domain\ValueObjects\Primitives\Text;

class Password extends Text
{
  protected int $minLen = 8;
}