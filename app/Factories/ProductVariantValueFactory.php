<?php

namespace App\Factories;

use App\Domain\ProductVariantValues\ProductVariantValue;
use App\Domain\ProductVariantValues\ValueId;
use App\Domain\ValueObjects\OptionId;
use App\Domain\ValueObjects\VariantId;
use App\DTO\ProductVariantValueInputDTO;

class ProductVariantValueFactory
{
  public function fromDTO(ProductVariantValueInputDTO $dto, ?ProductVariantValue $productVariantValue = null): ProductVariantValue
  {
    return new ProductVariantValue(
      variantId: $dto->variantId === null ? $productVariantValue?->variandId() : new VariantId($dto->variantId),
      optionId: $dto->optionId === null ? $productVariantValue?->optionId() : new OptionId($dto->optionId),
      valueId: $dto->valueId === null ? $productVariantValue?->valueId() : new ValueId($dto->valueId)
    );
  }
}