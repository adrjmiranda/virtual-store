<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class ProductOptionInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix;

  public function __construct(
    public ?int $productId,
    public ?string $name,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'productId' => 'trim|itrim',
      'name' => 'trim|itrim|htmlspecialchars|stripslashes',
    ];
  }

  public function validations(): array
  {
    return [
      'productId' => 'required|positive',
      'name' => 'required|string',
    ];
  }
}