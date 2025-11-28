<?php

namespace App\Domain\EventLogs;

use App\Domain\ValueObjects\Enum\EvenType;

class Type
{
  public function __construct(private readonly EvenType $value)
  {
  }

  public function value(): EvenType
  {
    return $this->value;
  }
}