<?php

namespace App\Domain\CartItems;

use App\Domain\ValueObjects\Primitives\Integer;

class Quantity extends Integer
{
  protected int $min = 1;
}