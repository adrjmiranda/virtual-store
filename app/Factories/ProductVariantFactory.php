<?php

namespace App\Factories;

use App\Domain\ProductVariants\ProductVariant;
use App\Domain\ProductVariants\Sku;
use App\Domain\ProductVariants\Stock;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\ProductId;
use App\DTO\ProductVariantInputDTO;

class ProductVariantFactory
{
  public function fromDTO(ProductVariantInputDTO $dto, ?ProductVariant $productVariant): ProductVariant
  {
    return new ProductVariant(
      id: $productVariant?->id(),
      productId: $dto->productId === null ? $productVariant?->productId() : new ProductId($dto->productId),
      sku: $dto->sku === null ? $productVariant?->sku() : new Sku($dto->sku),
      price: $dto->price === null ? $productVariant?->price() : new Price($dto->price),
      stock: $dto->stock === null ? $productVariant?->stock() : new Stock($dto->stock),
      isActive: $dto->isActive === null ? $productVariant?->isActive() : new IsActive($dto->isActive),
      createdAt: null,
      updatedAt: null
    );
  }
}