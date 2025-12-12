<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class CartInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'cart';

  public function __construct(
    public ?int $userId,
    public ?string $status,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'userId' => 'noop',
      'status' => 'trim|itrim|htmlspecialchars|stripslashes'
    ];
  }

  public function validations(): array
  {
    return [
      'userId' => 'required|positive',
      'status' => 'required|in:active:completed:abandoned'
    ];
  }
}
