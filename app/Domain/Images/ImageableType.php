<?php

namespace App\Domain\Images;

use App\Domain\ValueObjects\Primitives\Text;

class ImageableType extends Text
{
  protected int $maxLen = 100;
}