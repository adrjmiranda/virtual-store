<?php

namespace App\Services\Domain;

use App\Domain\Users\User;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\UserInputDTO;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserCreationException;
use App\Exceptions\UserUpdateExcepction;
use App\Factories\UserFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\UserRepository;
use Exception;

class UserService
{
  public function __construct(
    private UserRepository $repo,
    private UserFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(UserInputDTO $dto): ?User
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $this->v->validate($this->s->sanitize($dto));
      $user = $this->factory->fromDTO($dto);

      $userByEmail = $this->repo->forEmail($user->emailValue());
      if ($userByEmail) {
        throw new UserAlreadyExistsException();
      }

      $id = $this->repo->insert($user, User::FIELDS_INSERT);

      if ($id === null) {
        throw new UserCreationException();
      }

      $createdUser = $this->repo->find($id);

      if ($createdUser !== null) {
        $this->eventLog->record(
          EventType::CREATE,
          "Usuário '{$createdUser->nameValue()}' criado com email '{$createdUser->emailValue()}'"
        );
      }

      $this->repo->queryBuilder()->finishTransaction();

      return $createdUser;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw new UserCreationException();
    }
  }

  public function show(int $id): ?User
  {
    return $this->repo->find($id);
  }

  public function byEmail(string $email): ?User
  {
    return $this->repo->forEmail($email);
  }

  public function update(int $id, UserInputDTO $dto, array $fields): ?User
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $this->v->validate($this->s->sanitize($dto));

      $userToUpdate = $this->repo->find($id);
      if (!$userToUpdate) {
        throw new Exception('User not found.', 500);
      }
      $user = $this->factory->fromDTO($dto, $userToUpdate);
      $updated = $this->repo->update($user, $fields);

      if (!$updated) {
        throw new UserUpdateExcepction();
      }

      $updatedUser = $updated ? $this->repo->find($id) : null;

      if ($updatedUser !== null) {
        $this->eventLog->record(
          EventType::UPDATED,
          "Usuário '{$updatedUser->nameValue()}' atualizado com email '{$updatedUser->emailValue()}' (ID: {$id})"
        );
      }


      $this->repo->queryBuilder()->finishTransaction();

      return $updatedUser;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw new UserUpdateExcepction();
    }
  }

  public function delete()
  {
  }
}