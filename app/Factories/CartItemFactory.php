<?php

namespace App\Factories;

use App\Domain\CartItems\CartId;
use App\Domain\CartItems\CartItem;
use App\Domain\CartItems\Quantity;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\VariantId;
use App\DTO\CartItemInputDTO;

class CartItemFactory
{
  public function fromDTO(CartItemInputDTO $dto, ?CartItem $cartItem = null): CartItem
  {
    return new CartItem(
      id: $cartItem?->id(),
      cartId: $dto->cartId === null ? $cartItem?->cardId() : new CartId($dto->cartId),
      productId: $dto->productId === null ? $cartItem?->productId() : new ProductId($dto->productId),
      variantId: $dto->variantId === null ? $cartItem?->variantId() : new VariantId($dto->variantId),
      quantity: $dto->quantity === null ? $cartItem?->quantity() : new Quantity($dto->quantity),
      price: $dto->price === null ? $cartItem?->price() : new Price($dto->price),
      createdAt: null,
      updatedAt: null
    );
  }
}