<?php

namespace App\Factories;

use App\Domain\Products\Product;
use App\Domain\ProductVariants\Stock;
use App\Domain\Users\DeletedAt;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\Slug;
use App\DTO\ProductInputDTO;

class ProductFactory
{
  public function fromDTO(ProductInputDTO $dto, ?Product $product = null): Product
  {
    return new Product(
      id: $product?->id(),
      name: $dto->name === null ? $product?->name() : new Name($dto->name),
      slug: $dto->slug === null ? $product?->slug() : new Slug($dto->slug),
      description: $dto->description === null ? $product?->description() : new Description($dto->description),
      price: $dto->price === null ? $product?->price() : new Price($dto->price),
      stock: $dto->stock === null ? $product?->stock() : new Stock($dto->stock),
      isActive: $dto->isActive === null ? $product?->isActive() : new IsActive($dto->isActive),
      createdAt: null,
      updatedAt: null,
      deletedAt: $dto->deletedAt === null ? $product?->deleted() : new DeletedAt($dto->deletedAt)
    );
  }
}