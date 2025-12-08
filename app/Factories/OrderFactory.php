<?php

namespace App\Factories;

use App\Domain\Orders\Order;
use App\Domain\Orders\ShippingAddress;
use App\Domain\Orders\Status;
use App\Domain\Orders\Total;
use App\Domain\ValueObjects\Enum\OrderStatus;
use App\Domain\ValueObjects\UserId;
use App\DTO\OrderInputDTO;

class OrderFactory
{
  public function fromDTO(OrderInputDTO $dto, ?Order $order = null): Order
  {
    return new Order(
      id: $order?->id(),
      userId: $dto->userId === null ? $order?->userId() : new UserId($dto->userId),
      status: $dto->status === null ? $order?->status() : new Status(OrderStatus::from($dto->status)),
      total: $dto->total === null ? $order?->total() : new Total($dto->total),
      shippingAddress: $dto->shippingAddress === null ? $order?->shippingAddress() : new ShippingAddress($dto->shippingAddress),
      createdAt: null,
      updatedAt: null
    );
  }
}