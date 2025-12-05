<?php

namespace App\Domain\Users;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class User extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'name',
    'email',
    'password',
    'role',
  ];

  public function __construct(
    private ?Id $id,
    private Name $name,
    private Email $email,
    private ?EmailVerifiedAt $emailVerifiedAt,
    private Password $password,
    private Role $role,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt,
    private ?DeletedAt $deletedAt
  ) {
  }
}