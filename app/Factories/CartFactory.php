<?php

namespace App\Factories;

use App\Domain\Carts\Cart;
use App\Domain\Carts\Status;
use App\Domain\ValueObjects\Enum\CartStatus;
use App\Domain\ValueObjects\UserId;
use App\DTO\CartInputDTO;

class CartFactory
{
  public function fromDTO(CartInputDTO $dto, ?Cart $cart = null): Cart
  {
    return new Cart(
      id: $cart?->id(),
      userId: $dto->userId === null ? $cart?->userId() : new UserId($dto->userId),
      status: $dto->status === null ? $cart?->status() : new Status(CartStatus::from($dto->status)),
      createdAt: null,
      updatedAt: null
    );
  }
}