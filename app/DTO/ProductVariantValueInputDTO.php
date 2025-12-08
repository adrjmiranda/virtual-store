<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class ProductVariantValueInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'product_variant_value';

  public function __construct(
    public ?int $variantId,
    public ?int $optionId,
    public ?int $valueId
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'variantId' => 'trim|itrim',
      'optionId' => 'trim|itrim',
      'valueId' => 'trim|itrim',
    ];
  }

  public function validations(): array
  {
    return [
      'variantId' => 'required|positive',
      'optionId' => 'required|positive',
      'valueId' => 'required|positive',
    ];
  }
}