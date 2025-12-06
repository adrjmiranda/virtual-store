<?php

namespace App\Factories;

use App\Domain\Addresses\Address;
use App\Domain\Addresses\City;
use App\Domain\Addresses\Complement;
use App\Domain\Addresses\Country;
use App\Domain\Addresses\Number;
use App\Domain\Addresses\PostalCode;
use App\Domain\Addresses\State;
use App\Domain\Addresses\Street;
use App\Domain\ValueObjects\UserId;
use App\DTO\AddressInputDTO;

class AddressFactory
{
  public function fromDTO(AddressInputDTO $dto, ?Address $address = null): Address
  {
    return new Address(
      id: $address?->id(),
      userId: $dto->userId === null ? $address?->userId() : new UserId($dto->userId),
      street: $dto->street === null ? $address?->street() : new Street($dto->street),
      number: $dto->number === null ? $address?->number() : new Number($dto->number),
      complement: $dto->complement === null ? $address?->complement() : new Complement($dto->complement),
      city: $dto->city === null ? $address?->city() : new City($dto->city),
      state: $dto->state === null ? $address?->state() : new State($dto->state),
      country: $dto->country === null ? $address->country() : new Country($dto->country),
      postalCode: $dto->postalCode === null ? $address->postalCode() : new PostalCode($dto->postalCode),
      createdAt: null,
      updatedAt: null
    );
  }
}
