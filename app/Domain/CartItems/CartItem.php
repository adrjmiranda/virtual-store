<?php

namespace App\Domain\CartItems;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\VariantId;

class CartItem extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private CartId $cartId,
    private ProductId $productId,
    private ?VariantId $variantId,
    private Quantity $quantity,
    private Price $price,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}