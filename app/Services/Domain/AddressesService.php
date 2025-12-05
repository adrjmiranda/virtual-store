<?php

namespace App\Services\Domain;

use App\Domain\Addresses\Address;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\AddressInputDTO;
use App\Exceptions\AddressCreationException;
use App\Exceptions\AddressRemoveException;
use App\Exceptions\AddressUpdateException;
use App\Factories\AddressFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\AddressRepository;
use App\Repository\EventLogRepository;
use Exception;

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

  public function update(AddressInputDTO $dto): ?Address
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $this->v->validate($this->s->sanitize($dto));

      $address = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($address, Address::FIELDS_UPDATE);

      if (!$updated) {
        throw new AddressUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Endereço atualizado para o usuário {$dto->userId}: {$dto->street}, {$dto->number}, {$dto->city} - {$dto->state}, {$dto->country}, CEP {$dto->postalCode}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updated ? $this->repo->find($address->idValue()) : null;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw new AddressUpdateException();
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();

      $address = $this->repo->find($id);
      if (!$address) {
        throw new Exception('Address not found', 404);
      }

      $deleted = $this->repo->delete($address);
      if (!$deleted) {
        throw new AddressRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Endereço removido para o usuário {$address->userIdValue()}: {$address->streetValue()}, {$address->numberValue()}, {$address->cityValue()} - {$address->stateValue()}, {$address->countryValue()}, CEP {$address->postalCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw new AddressRemoveException();
    }
  }
}