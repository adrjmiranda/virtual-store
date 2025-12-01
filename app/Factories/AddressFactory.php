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
  public function fromDTO(AddressInputDTO $dto): Address
  {
    return new Address(
      id: null,
      userId: new UserId($dto->userId),
      street: new Street($dto->street),
      number: $dto->number ? new Number($dto->number) : null,
      complement: $dto->complement ? new Complement($dto->complement) : null,
      city: new City($dto->city),
      state: new State($dto->state),
      country: new Country($dto->country),
      postalCode: new PostalCode($dto->postalCode),
      createdAt: null,
      updatedAt: null
    );
  }
}
