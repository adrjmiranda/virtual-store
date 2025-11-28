<?php

namespace App\Domain\OrderItems;

use App\Domain\ValueObjects\Primitives\Integer;

class Quantity extends Integer
{
  protected int $min = 1;
}