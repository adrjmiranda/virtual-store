<?php

namespace App\Services\Domain;

use App\Domain\ProductVariantValues\ProductVariantValue;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductVariantValueInputDTO;
use App\Exceptions\ProductVariantValueCreationException;
use App\Factories\ProductVariantValueFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductVariantValueRepository;

class ProductVariantValueService
{
  public function __construct(
    private ProductVariantValueRepository $repo,
    private ProductVariantValueFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(ProductVariantValueInputDTO $dto): ?ProductVariantValue
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $productVariantValue = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($productVariantValue, ProductVariantValue::FIELDS_INSERT);

      if ($id === null) {
        throw new ProductVariantValueCreationException();
      }

      $createdProductVariantValue = $this->repo->find($id);
      if ($createdProductVariantValue === null) {
        throw new ProductVariantValueCreationException();
      }


      $this->eventLog->record(
        EventType::CREATE,
        "Variante e valor de producto criada com OptionID: {$createdProductVariantValue->optionIdValue()} e ValueID: {$createdProductVariantValue->valueIdValue()}"
      );


      $this->repo->queryBuilder()->finishTransaction();
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}