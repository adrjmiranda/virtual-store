<?php

namespace App\Services\Domain;

use App\Domain\ProductOptions\ProductOption;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductOptionInputDTO;
use App\Exceptions\ProductOptionCreationException;
use App\Factories\ProductOptionFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductOptionRepository;

class ProductOptionService
{
  public function __construct(
    private ProductOptionRepository $repo,
    private ProductOptionFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(ProductOptionInputDTO $dto): ?ProductOption
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $productOption = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($productOption, ProductOption::FIELDS_INSERT);

      if ($id === null) {
        throw new ProductOptionCreationException();
      }

      $createdProductOption = $this->repo->find($id);
      if ($createdProductOption === null) {
        throw new ProductOptionCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Opção de produto criado ID: {$createdProductOption->idValue()} - nome: {$createdProductOption->nameValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdProductOption;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}