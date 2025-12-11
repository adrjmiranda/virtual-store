<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class ProductVariantInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'product_variant';

  public function __construct(
    public ?int $productId,
    public ?string $sku,
    public ?int $price,
    public ?int $stock,
    public ?bool $isActive,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'productId' => 'noop',
      'sku' => 'trim|itrim|htmlspecialchars|stripslashes',
      'price' => 'noop',
      'stock' => 'noop',
      'isActive' => 'noop',
    ];
  }

  public function validations(): array
  {
    return [
      'productId' => 'required|positive',
      'sku' => 'required|alphanumeric',
      'price' => 'required|posandzero',
      'stock' => 'required|posandzero',
      'isActive' => 'required|boolean',
    ];
  }
}