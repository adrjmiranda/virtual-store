<?php

namespace App\Domain\ProductVariantValues;

use App\Domain\ValueObjects\OptionId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\VariantId;

class ProductVariantValue extends DatabaseEntity
{

  public const array FIELDS_INSERT = [
    'variant_id',
    'option_id',
    'value_id',
  ];

  public function __construct(
    private VariantId $variantId,
    private OptionId $optionId,
    private ValueId $valueId
  ) {
  }
}