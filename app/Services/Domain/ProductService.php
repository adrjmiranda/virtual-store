<?php

namespace App\Services\Domain;

use App\Domain\Products\Product;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\ProductInputDTO;
use App\Exceptions\ProductCreationException;
use App\Exceptions\ProductSlugAlreadyExists;
use App\Exceptions\ProductUpdateException;
use App\Factories\ProductFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductRepository;
use Exception;

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
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $product = $this->factory->fromDTO($dto);

      $productBySlug = $this->repo->forSlug($product->slugValue());
      if ($productBySlug !== null) {
        throw new ProductSlugAlreadyExists();
      }

      $id = $this->repo->insert($product, Product::FIELDS_INSERT);
      if ($id === null) {
        throw new ProductCreationException();
      }

      $createdProduct = $this->repo->find($id);
      if ($createdProduct === null) {
        throw new ProductCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Produto criado ID: {$createdProduct->idValue()} - nome: {$createdProduct->nameValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdProduct;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Product
  {
    return $this->repo->find($id);
  }

  public function bySlug(string $slug): ?Product
  {
    return $this->repo->forSlug($slug);
  }

  public function update(int $id, ProductInputDTO $dto, array $fields): ?Product
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $productToUpdate = $this->repo->find($id);
      if ($productToUpdate === null) {
        throw new Exception("Product not found", 500);
      }
      $product = $this->factory->fromDTO($dto, $productToUpdate);

      $productBySlug = $this->repo->forSlug($product->slugValue());
      if ($productBySlug !== null && $productToUpdate !== $productBySlug->slugValue()) {
        throw new ProductSlugAlreadyExists();
      }

      $updated = $this->repo->update($product, $fields);
      if (!$updated) {
        throw new ProductUpdateException();
      }

      $updatedProduct = $updated ? $this->repo->find($id) : null;
      if ($updatedProduct === null) {
        throw new ProductUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Produto criado ID: {$updatedProduct->idValue()} - nome: {$updatedProduct->nameValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedProduct;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}