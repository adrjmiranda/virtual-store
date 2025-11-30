<?php

namespace App\Domain\ProductVariantValues;

use App\Domain\ValueObjects\OptionId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\VariantId;

class ProductVariantValue extends DatabaseEntity
{
  public function __construct(
    private VariantId $variantId,
    private OptionId $optionId,
    private ValueId $valueId
  ) {
  }
}