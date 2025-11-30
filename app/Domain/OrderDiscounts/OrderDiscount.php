<?php

namespace App\Domain\OrderDiscounts;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\OrderId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class OrderDiscount extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private OrderId $orderId,
    private DiscountCode $discountCode,
    private DiscountAmount $discountAmount,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}