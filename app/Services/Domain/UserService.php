<?php

namespace App\Services\Domain;

use App\Domain\Users\User;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\UserInputDTO;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserCreationException;
use App\Factories\UserFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\UserRepository;

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

  public function show()
  {
  }

  public function update()
  {
  }

  public function delete()
  {
  }
}