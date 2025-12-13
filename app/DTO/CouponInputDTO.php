<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class CouponInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'coupon';

  public function __construct(
    public ?string $code,
    public ?string $type,
    public ?int $value,
    public ?int $usageLimit,
    public ?int $usedCount,
    public ?string $startsAt,
    public ?string $expiresAt,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'code' => 'trim|itrim|htmlspecialchars|stripslashes',
      'type' => 'trim|itrim|htmlspecialchars|stripslashes',
      'value' => 'noop',
      'usageLimit' => 'noop',
      'usedCount' => 'noop',
      'startsAt' => 'trim|itrim',
      'expiresAt' => 'trim|itrim',
    ];
  }

  public function validations(): array
  {
    return [
      'code' => 'required|alphanumeric',
      'type' => 'required|in:percent:fixed',
      'value' => 'required|positive',
      'usageLimit' => 'positive',
      'usedCount' => 'required',
      'startsAt' => 'required|date',
      'expiresAt' => 'date',
    ];
  }
}