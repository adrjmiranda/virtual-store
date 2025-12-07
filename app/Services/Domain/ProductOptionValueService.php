<?php

namespace App\Services\Domain;

use App\Domain\ProductOptionValues\ProductOptionValue;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductOptionValueInputDTO;
use App\Exceptions\ProductOptionValueCreationException;
use App\Exceptions\ProductOptionValueRemoveException;
use App\Exceptions\ProductOptionValueUpdateException;
use App\Factories\ProductOptionValueFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductOptionValueRepository;
use Exception;

class ProductOptionValueService
{
  public function __construct(
    private ProductOptionValueRepository $repo,
    private ProductOptionValueFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(ProductOptionValueInputDTO $dto): ?ProductOptionValue
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $productOptionValue = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($productOptionValue, ProductOptionValue::FIELDS_INSERT);

      if ($id === null) {
        throw new ProductOptionValueCreationException();
      }

      $createdProductOptionValue = $this->repo->find($id);
      if ($createdProductOptionValue === null) {
        throw new ProductOptionValueCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Opção e valor de produto criado ID: {$createdProductOptionValue->idValue()} - opção: {$createdProductOptionValue->optionIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdProductOptionValue;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?ProductOptionValue
  {
    return $this->repo->find($id);
  }

  public function update(int $id, ProductOptionValueInputDTO $dto, array $fields): ?ProductOptionValue
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $productionOptionValueToUpdate = $this->repo->find($id);
      if ($productionOptionValueToUpdate === null) {
        throw new Exception('Product option value not found.', 500);
      }
      $productOptionValue = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($productOptionValue, $fields);


      if (!$updated) {
        throw new ProductOptionValueUpdateException();
      }

      $updatedProductOptionValue = $updated ? $this->repo->find($id) : null;
      if ($updatedProductOptionValue === null) {
        throw new ProductOptionValueUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Opção e valor de produto atualizado ID: {$updatedProductOptionValue->idValue()} - opção: {$updatedProductOptionValue->optionIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedProductOptionValue;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();

      $productionOptionValue = $this->repo->find($id);
      if ($productionOptionValue === null) {
        throw new Exception('Product option value not found.', 500);
      }

      $deleted = $this->repo->delete($productionOptionValue);
      if (!$deleted) {
        throw new ProductOptionValueRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Opção e valor de produto removido ID: {$id} - opção: {$productionOptionValue->optionIdValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}