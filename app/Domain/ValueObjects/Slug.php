<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Primitives\Text;

class Slug extends Text
{
  protected int $maxLen = 272;
}