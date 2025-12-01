<?php

namespace App\Domain\Addresses;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class Address extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'user_id',
    'street',
    'number',
    'complement',
    'city',
    'state',
    'country',
    'postal_code'
  ];


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
}