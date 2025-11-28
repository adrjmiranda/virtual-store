<?php

namespace App\Domain\Addresses;

use App\Domain\ValueObjects\Contracts\DatabaseEntityInterface;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class Address implements DatabaseEntityInterface
{
  public function __construct(
    private ?Id $id,
    private UserId $userId,
    private Street $street,
    private ?Number $number,
    private ?Complement $complement,
    private City $city,
    private State $state,
    private Country $country,
    private PostalCode $postalCode,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }

  public static function fromDatabase(array $data): static
  {
    return new self(
      new Id($data['id']),
      new UserId($data['user_id']),
      new Street($data['street']),
      $data['number'] === null ? null : new Number($data['number']),
      $data['complement'] === null ? null : new Complement($data['complement']),
      new City($data['city']),
      new State($data['state']),
      new Country($data['country']),
      new PostalCode($data['postal_code']),
      new CreatedAt($data['created_at']),
      new UpdatedAt($data['updated_at'])
    );
  }

  public function toDatabase(): array
  {
    return [
      'user_id' => $this->userId->value(),
      'street' => $this->street->value(),
      'number' => $this->number?->value(),
      'complement' => $this->complement?->value(),
      'city' => $this->city->value(),
      'state' => $this->state->value(),
      'country' => $this->country->value(),
      'postal_code' => $this->postalCode->value(),
    ];
  }
}