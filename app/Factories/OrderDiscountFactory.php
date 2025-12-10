<?php

namespace App\Factories;

use App\Domain\OrderDiscounts\DiscountAmount;
use App\Domain\OrderDiscounts\DiscountCode;
use App\Domain\OrderDiscounts\OrderDiscount;
use App\Domain\ValueObjects\OrderId;
use App\DTO\OrderDiscountInputDTO;

class OrderDiscountFactory
{
  public function fromDTO(OrderDiscountInputDTO $dto, ?OrderDiscount $orderDiscount = null): OrderDiscount
  {
    return new OrderDiscount(
      id: $orderDiscount?->id(),
      orderId: $dto->orderId === null ? $orderDiscount?->orderId() : new OrderId($dto->orderId),
      discountCode: $dto->discountCode === null ? $orderDiscount?->discountCode() : new DiscountCode($dto->discountCode),
      discountAmount: $dto->discountAmount === null ? $orderDiscount?->discountAmount() : new DiscountAmount($dto->discountAmount),
      createdAt: null,
      updatedAt: null
    );
  }
}