<?php

namespace App\Domain\OrderPayments;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\OrderId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;

class OrderPayment extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private OrderId $orderId,
    private PaymentId $paymentId,
    private ?CreatedAt $createdAt
  ) {
  }
}