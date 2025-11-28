<?php

namespace App\Domain\ValueObjects\Enum;

enum OrderStatus: string
{
  case PENDING = 'pending';
  case PROCESSING = 'processing';
  case COMPLETED = 'completed';
  case CANCELED = 'canceled';
}