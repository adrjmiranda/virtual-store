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
      'userId' => 'required|numeric',
      'street' => 'required|numeric',
      'number' => 'numeric',
      'complement' => 'numeric',
      'city' => 'required|numeric',
      'state' => 'required|numeric',
      'country' => 'required|numeric',
      'postalCode' => 'required|numeric',
    ];
  }

  public function fieldPrefix(): string
  {
    return "{$this->fieldPrefix}_";
  }

  public function validations(): array
  {
    return [
      'userId' => 'required|numeric',
      'street' => 'required|numeric',
      'number' => 'numeric',
      'complement' => 'numeric',
      'city' => 'required|numeric',
      'state' => 'required|numeric',
      'country' => 'required|numeric',
      'postalCode' => 'required|numeric',
    ];
  }
}