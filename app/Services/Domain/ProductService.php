<?php

namespace App\Services\Domain;

use App\Domain\Products\Product;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductInputDTO;
use App\Exceptions\ProductCreationException;
use App\Factories\ProductFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductRepository;

class ProductService
{
  public function __construct(
    private ProductRepository $repo,
    private ProductFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(ProductInputDTO $dto): ?Product
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $this->v->validate($this->s->sanitize($dto));
      $product = $this->factory->fromDTO($dto);

      $id = $this->repo->insert($product, Product::FIELDS_INSERT);


      if ($id === null) {
        throw new ProductCreationException();
      }

      $createdProduct = $this->repo->find($id);

      if ($createdProduct !== null) {
        $this->eventLog->record(
          EventType::CREATE,
          "Produto criado ID: {$createdProduct->idValue()} - nome: {$createdProduct->nameValue()}"
        );
      }

      $this->repo->queryBuilder()->finishTransaction();

      return $createdProduct;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
    }
  }
}