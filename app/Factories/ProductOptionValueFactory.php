<?php

namespace App\Factories;

use App\Domain\ProductOptionValues\ProductOptionValue;
use App\Domain\ProductOptionValues\Value;
use App\Domain\ValueObjects\OptionId;
use App\DTO\ProductOptionValueInputDTO;

class ProductOptionValueFactory
{
  public function fromDTO(ProductOptionValueInputDTO $dto, ?ProductOptionValue $productOptionValue = null): ProductOptionValue
  {
    return new ProductOptionValue(
      id: $productOptionValue?->id(),
      optionId: $dto->optionId === null ? $productOptionValue?->optionId() : new OptionId($dto->optionId),
      value: $dto->value === null ? $productOptionValue?->value() : new Value($dto->value),
      createdAt: null,
      updatedAt: null
    );
  }
}