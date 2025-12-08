<?php

namespace App\Services\Domain;

use App\Domain\ProductVariants\ProductVariant;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductVariantInputDTO;
use App\Exceptions\ProductVariantCreationException;
use App\Factories\ProductVariantFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductVariantRepository;

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


  // ToDo:
  // Function UPDATE


  // ToDo:
  // Function REMOVE
}