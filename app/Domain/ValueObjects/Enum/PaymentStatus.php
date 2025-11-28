<?php

namespace App\Domain\ValueObjects\Enum;

enum PaymentStatus: string
{
  case PEDING = 'pending';
  case PAID = 'paid';
  case FAILED = 'failed';
  case CANCELED = 'canceled';
}