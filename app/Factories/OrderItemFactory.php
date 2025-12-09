<?php

namespace App\Factories;

use App\Domain\OrderItems\Discount;
use App\Domain\OrderItems\OrderItem;
use App\Domain\OrderItems\Quantity;
use App\Domain\OrderItems\UnitPrice;
use App\Domain\ValueObjects\OrderId;
use App\Domain\ValueObjects\ProductId;
use App\Domain\ValueObjects\VariantId;
use App\DTO\OrderItemInputDTO;

class OrderItemFactory
{
  public function fromDTO(OrderItemInputDTO $dto, ?OrderItem $orderItem = null): OrderItem
  {
    return new OrderItem(
      id: $orderItem?->id(),
      orderId: $dto->orderId === null ? $orderItem?->orderId() : new OrderId($dto->orderId),
      productId: $dto->productId === null ? $orderItem?->productId() : new ProductId($dto->productId),
      variantId: $dto->variantId === null ? $orderItem?->variantId() : new VariantId($dto->variantId),
      quantity: $dto->quantity === null ? $orderItem?->quantitty() : new Quantity($dto->quantity),
      unitPrice: $dto->unitPrice === null ? $orderItem?->unitPrice() : new UnitPrice($dto->unitPrice),
      discount: $dto->discount === null ? $orderItem?->discount() : new Discount($dto->discount),
      createdAt: null,
      updatedAt: null
    );
  }
}