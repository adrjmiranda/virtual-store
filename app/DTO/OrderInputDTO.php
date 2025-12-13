<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class OrderInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'order';

  public function __construct(
    public ?int $userId,
    public ?string $status,
    public ?int $total,
    public ?string $shippingAddress,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'userId' => 'noop',
      'status' => 'trim|itrim|htmlspecialchars|stripslashes',
      'total' => 'noop',
      'shippingAddress' => 'trim|itrim|htmlspecialchars|stripslashes',
    ];
  }

  public function validations(): array
  {
    return [
      'userId' => 'required|positive',
      'status' => 'required|in:pending:processing:completed:canceled',
      'total' => 'required|posandzero',
      'shippingAddress' => 'string',
    ];
  }
}