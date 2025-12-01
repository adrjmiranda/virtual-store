<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class AddressInputDTO implements SanitizableDTO, ValidatableDTO
{
  private string $fieldPrefix = 'address';

  public function __construct(
    public int $userId,
    public string $street,
    public ?string $number,
    public ?string $complement,
    public string $city,
    public string $state,
    public string $country,
    public string $postalCode,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'userId' => 'trim|itrim|htmlspecialchars',
      'street' => 'trim|itrim|htmlspecialchars|stripslashes',
      'number' => 'trim|itrim|htmlspecialchars|stripslashes',
      'complement' => 'trim|itrim|htmlspecialchars|stripslashes',
      'city' => 'trim|itrim|htmlspecialchars',
      'state' => 'trim|itrim|htmlspecialchars|strtoupper',
      'country' => 'trim|itrim|htmlspecialchars|strtoupper',
      'postalCode' => 'trim|itrim|htmlspecialchars',
    ];
  }

  public function fieldPrefix(): string
  {
    return "{$this->fieldPrefix}_";
  }

  public function validations(): array
  {
    return [
      'userId' => 'required|positive',
      'street' => 'required|string',
      'number' => 'string',
      'complement' => 'string',
      'city' => 'required|alphabetic',
      'state' => 'required|acronym',
      'country' => 'required|acronym',
      'postalCode' => 'required|numeric',
    ];
  }
}