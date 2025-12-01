<?php

namespace App\Domain\EventLogs;

use App\Domain\ValueObjects\Enum\EventType;

class Type
{
  public function __construct(private readonly EventType $value)
  {
  }

  public function value(): EventType
  {
    return $this->value;
  }
}