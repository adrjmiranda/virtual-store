<?php

namespace App\Domain\ProductOptions;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class ProductOption extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'product_id',
    'name',
  ];

  public function __construct(
    private ?Id $id,
    private ProductId $productId,
    private Name $name,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}