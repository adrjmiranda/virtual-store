<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class PaymentInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'payment';

  public function __construct(
    public ?int $userId,
    public ?int $amount,
    public ?string $method,
    public ?string $status,
    public ?string $transactionId,
    public ?string $paidAt,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'userId' => 'trim|itrim',
      'amount' => 'trim|itrim',
      'method' => 'trim|itrim|htmlspecialchars|stripslashes',
      'status' => 'trim|itrim|htmlspecialchars|stripslashes',
      'transactionId' => 'trim|itrim|htmlspecialchars|stripslashes',
      'paidAt' => 'trim|itrim',
    ];
  }

  public function validations(): array
  {
    return [
      'userId' => 'required|positive',
      'amount' => 'required|posandzero',
      'method' => 'required|alphaaccents',
      'status' => 'required|in:pending:paid:failed:canceled',
      'transactionId' => 'string',
      'paidAt' => 'date',
    ];
  }
}