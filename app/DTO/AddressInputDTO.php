<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class AddressInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'address';

  public function __construct(
    public ?int $userId = null,
    public ?string $street = null,
    public ?string $number = null,
    public ?string $complement = null,
    public ?string $city = null,
    public ?string $state = null,
    public ?string $country = null,
    public ?string $postalCode = null,
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