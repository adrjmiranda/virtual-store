<?php

namespace App\Services\Domain;

use App\Domain\Addresses\Address;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\AddressInputDTO;
use App\Exceptions\AddressCreationException;
use App\Factories\AddressFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\AddressRepository;
use App\Repository\EventLogRepository;

class AddressesService
{
  public function __construct(
    private AddressRepository $repo,
    private AddressFactory $factory,
    private Sanitization $s,
    private Validation $v,
    private EventLogRepository $eventLog,
  ) {
  }

  public function create(AddressInputDTO $dto): ?Address
  {
    try {
      $this->repo->queryBuilder()->startTransaction();

      $this->v->validate($this->s->sanitize($dto));

      $address = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($address, Address::FIELDS_INSERT);

      if ($id === null) {
        throw new AddressCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Endereço criado para o usuário {$dto->userId}: {$dto->street}, {$dto->number}, {$dto->city} - {$dto->state}, {$dto->country}, CEP {$dto->postalCode}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $this->repo->find($id);
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw new AddressCreationException();
    }
  }

  public function show(int $id): ?Address
  {
    return $this->repo->find($id);
  }

  public function byUser(int $userId): array
  {
    return $this->repo->forUser($userId);
  }
}