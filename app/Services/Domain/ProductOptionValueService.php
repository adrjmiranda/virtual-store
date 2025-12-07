<?php

namespace App\Services\Domain;

use App\Domain\ProductOptionValues\ProductOptionValue;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductOptionValueInputDTO;
use App\Exceptions\ProductOptionValueCreationException;
use App\Factories\ProductOptionValueFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductOptionValueRepository;

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
}