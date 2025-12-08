<?php

namespace App\Domain\ProductVariants;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class ProductVariant extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'product_id',
    'sku',
    'price',
    'stock',
    'is_active',
  ];

  public function __construct(
    private ?Id $id,
    private ProductId $productId,
    private Sku $sku,
    private Price $price,
    private Stock $stock,
    private IsActive $isActive,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}