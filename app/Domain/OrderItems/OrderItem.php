<?php

namespace App\Domain\OrderItems;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\OrderId;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\VariantId;

class OrderItem extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'order_id',
    'product_id',
    'variant_id',
    'quantitty',
    'unit_price',
    'discount',
  ];

  public function __construct(
    private ?Id $id,
    private OrderId $orderId,
    private ProductId $productId,
    private ?VariantId $variantId,
    private Quantity $quantity,
    private UnitPrice $unitPrice,
    private Discount $discount,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}