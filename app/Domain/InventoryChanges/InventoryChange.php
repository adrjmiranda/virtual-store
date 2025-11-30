<?php

namespace App\Domain\InventoryChanges;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\VariantId;

class InventoryChange extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private ProductId $productId,
    private ?VariantId $variantId,
    private ChangeQuantity $changeQuantity,
    private ?Reason $reason,
    private ?CreatedBy $createdBy,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}