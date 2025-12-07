<?php

namespace App\Factories;

use App\Domain\Users\DeletedAt;
use App\Domain\Users\Email;
use App\Domain\Users\EmailVerifiedAt;
use App\Domain\Users\Password;
use App\Domain\Users\Role;
use App\Domain\Users\User;
use App\Domain\ValueObjects\Enum\UserRole;
use App\Domain\ValueObjects\Name;
use App\DTO\UserInputDTO;

class UserFactory
{
  public function fromDTO(UserInputDTO $dto, ?User $user = null): User
  {
    return new User(
      id: $user?->id(),
      name: $dto->name === null ? $user?->name() : new Name($dto->name),
      email: $dto->email === null ? $user?->email() : new Email($dto->email),
      emailVerifiedAt: $dto->emailVerifiedAt === null ? $user?->emailVerifiedAt() : new EmailVerifiedAt($dto->emailVerifiedAt),
      password: $dto->password === null ? $user?->password() : new Password($dto->password),
      role: $dto->role === null ? $user?->role() : new Role(UserRole::from($dto->role)),
      createdAt: null,
      updatedAt: null,
      deletedAt: $dto->deletedAt === null ? $user?->deleted() : new DeletedAt($dto->deletedAt)
    );
  }
}