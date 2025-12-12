<?php

namespace App\Domain\Carts;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class Cart extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'user_id',
    'status',
  ];

  public function __construct(
    private ?Id $id,
    private UserId $userId,
    private Status $status,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
    $status->value()->value;
  }
}