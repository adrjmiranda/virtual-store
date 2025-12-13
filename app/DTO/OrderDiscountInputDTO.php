<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class OrderDiscountInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'order_discount';

  public function __construct(
    public ?int $orderId,
    public ?string $discountCode,
    public ?int $discountAmount,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'orderId' => 'noop',
      'discountCode' => 'trim|itrim|htmlspecialchars|stripslashes',
      'discountAmount' => 'noop'
    ];
  }

  public function validations(): array
  {
    return [
      'orderId' => 'required|positive',
      'discountCode' => 'required|alphanumeric',
      'discountAmount' => 'required|posandzero'
    ];
  }
}