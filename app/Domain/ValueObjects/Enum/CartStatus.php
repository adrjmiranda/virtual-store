<?php

namespace App\Domain\ValueObjects\Enum;

enum CartStatus: string
{
  case ACTIVE = 'active';
  case COMPLETED = 'completed';
  case ABANDONED = 'abandoned';
}