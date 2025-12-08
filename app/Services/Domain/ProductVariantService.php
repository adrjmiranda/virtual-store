<?php

namespace App\Services\Domain;

use App\Domain\ProductVariants\ProductVariant;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductVariantInputDTO;
use App\Exceptions\ProductVariantCreationException;
use App\Exceptions\ProductVariantRemoveException;
use App\Exceptions\ProductVariantUpdateException;
use App\Factories\ProductVariantFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductVariantRepository;
use Exception;

class ProductVariantService
{
  public function __construct(
    private ProductVariantRepository $repo,
    private ProductVariantFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(ProductVariantInputDTO $dto): ?ProductVariant
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $productVariant = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($productVariant, ProductVariant::FIELDS_INSERT);

      if ($id === null) {
        throw new ProductVariantCreationException();
      }

      $createdProductVariant = $this->repo->find($id);
      if ($createdProductVariant === null) {
        throw new ProductVariantCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Variante de produto criada ID {$id} sku {$createdProductVariant->skuValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdProductVariant;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?ProductVariant
  {
    return $this->repo->find($id);
  }

  public function update(int $id, ProductVariantInputDTO $dto, array $fields): ?ProductVariant
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $productVariantToUpdate = $this->repo->find($id);
      if ($productVariantToUpdate === null) {
        throw new Exception("Product variant not found", 500);
      }
      $productVarint = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($productVarint, $fields);

      if (!$updated) {
        throw new ProductVariantUpdateException();
      }

      $updatedProductVariant = $updated ? $this->repo->find($id) : null;
      if ($updatedProductVariant === null) {
        throw new ProductVariantUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Variação de produto atualizada ID: {$id} - SKU: {$updatedProductVariant->skuValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedProductVariant;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();

      $productVariant = $this->repo->find($id);
      if ($productVariant === null) {
        throw new Exception("Product variant not found", 500);
      }

      $deleted = $this->repo->delete($productVariant);
      if (!$deleted) {
        throw new ProductVariantRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Variação de produto removida ID: {$id} - SKU: {$productVariant->skuValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}