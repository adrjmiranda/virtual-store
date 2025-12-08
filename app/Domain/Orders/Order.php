<?php

namespace App\Domain\Orders;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class Order extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'user_id',
    'status',
    'total',
    'shipping_address',
  ];

  public function __construct(
    private ?Id $id,
    private UserId $userId,
    private Status $status,
    private Total $total,
    private ?ShippingAddress $shippingAddress,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}