<?php

namespace App\Domain\ProductCategory;

use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;

class ProductCategory extends DatabaseEntity
{
  public function __construct(
    private ProductId $productId,
    private CategoryId $categoryId
  ) {
  }
}