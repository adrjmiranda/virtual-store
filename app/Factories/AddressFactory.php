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
      userId: $address === null ? new UserId($dto->userId) : $address->userId(),
      street: $address === null ? new Street($dto->street) : $address->street(),
      number: $address === null ? ($dto->number ? new Number($dto->number) : null) : $address->number(),
      complement: $address === null ? ($dto->complement ? new Complement($dto->complement) : null) : $address->complement(),
      city: $address === null ? new City($dto->city) : $address->city(),
      state: $address === null ? new State($dto->state) : $address->state(),
      country: $address === null ? new Country($dto->country) : $address->country(),
      postalCode: $address === null ? new PostalCode($dto->postalCode) : $address->postalCode(),
      createdAt: null,
      updatedAt: null
    );
  }
}
