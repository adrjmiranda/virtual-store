<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class ProductOptionValueInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'product_option_value';

  public function __construct(
    public ?int $optionId,
    public ?string $value,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'optionId' => 'trim|itrim',
      'value' => 'trim|itrim|htmlspecialchars|stripslashes'
    ];
  }

  public function validations(): array
  {
    return [
      'optionId' => 'required|positive',
      'value' => 'required|string'
    ];
  }
}