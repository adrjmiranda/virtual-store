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
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $address = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($address, Address::FIELDS_INSERT);

      if ($id === null) {
        throw new AddressCreationException();
      }

      $createdAddress = $this->repo->find($id);
      if ($createdAddress === null) {
        throw new AddressCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Endereço criado para o usuário {$createdAddress->userIdValue()}: {$createdAddress->streetValue()}, {$createdAddress->numberValue()}, {$createdAddress->cityValue()} - {$createdAddress->stateValue()}, {$createdAddress->countryValue()}, CEP {$createdAddress->postalCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdAddress;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
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

  public function update(int $id, AddressInputDTO $dto, array $fields): ?Address
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $addressToUpdate = $this->repo->find($id);
      if (!$addressToUpdate) {
        throw new Exception('Address not found.', 500);
      }
      $address = $this->factory->fromDTO($dto, $addressToUpdate);
      $updated = $this->repo->update($address, $fields);

      if (!$updated) {
        throw new AddressUpdateException();
      }

      $updatedAddress = $updated ? $this->repo->find($address->idValue()) : null;
      if ($updatedAddress === null) {
        throw new AddressUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Endereço atualizado para o usuário {$updatedAddress->userIdValue()}: {$updatedAddress->streetValue()}, {$updatedAddress->numberValue()}, {$updatedAddress->cityValue()} - {$updatedAddress->stateValue()}, {$updatedAddress->countryValue()}, CEP {$updatedAddress->postalCodeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedAddress;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();

      $address = $this->repo->find($id);
      if ($address === null) {
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
      throw $th;
    }
  }
}