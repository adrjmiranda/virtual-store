<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class UserInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'user';

  public function __construct(
    public ?string $name = null,
    public ?string $email = null,
    public ?string $password = null,
    public ?string $role = 'user',
    public ?string $emailVerifiedAt = null,
    public ?string $deletedAt = null,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'name' => 'trim|itrim|htmlspecialchars|stripslashes',
      'email' => 'trim|itrim|lowercase|emailsanitize',
      'password' => 'trim',
      'role' => 'trim|lowercase',
      'emailVerifiedAt' => 'trim',
      'deletedAt' => 'trim',
    ];
  }

  public function validations(): array
  {
    return [
      'name' => 'required|string|min:2|max:255',
      'email' => 'required|email',
      'password' => 'required|string|min:8|max:255',
      'role' => 'in:user:admin:manager',

      'emailVerifiedAt' => 'date',
      'deletedAt' => 'date',
    ];
  }
}
