<?php

namespace App\Factories;

use App\Domain\ProductOptions\Name;
use App\Domain\ProductOptions\ProductOption;
use App\Domain\ValueObjects\ProductId;
use App\DTO\ProductOptionInputDTO;

class ProductOptionFactory
{
  public function fromDTO(ProductOptionInputDTO $dto, ?ProductOption $productOption = null): ProductOption
  {
    return new ProductOption(
      id: $productOption?->id(),
      productId: $dto->productId === null ? $productOption?->productId() : new ProductId($dto->productId),
      name: $dto->name === null ? $productOption?->name() : new Name($dto->name),
      createdAt: null,
      updatedAt: null
    );
  }
}