<?php

namespace App\Services\Domain;

use App\Domain\Categories\Category;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CategoryInputDTO;
use App\Exceptions\CategoryCreationException;
use App\Exceptions\CategoryRemoveException;
use App\Exceptions\CategorySlugAlreadyExists;
use App\Exceptions\CategoryUpdateExcepction;
use App\Factories\CategoryFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CategoryRepository;
use App\Repository\EventLogRepository;
use Exception;

class CategoryService
{
  public function __construct(
    private CategoryRepository $repo,
    private CategoryFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(CategoryInputDTO $dto): ?Category
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $category = $this->factory->fromDTO($dto);

      $categoryBySlug = $this->repo->forSlug($category->slugValue());
      if ($categoryBySlug !== null) {
        throw new CategorySlugAlreadyExists();
      }

      $id = $this->repo->insert($category, Category::FIELDS_INSERT);
      if ($id === null) {
        throw new CategoryCreationException();
      }

      $createdCategory = $this->repo->find($id);
      if ($createdCategory === null) {
        throw new CategoryCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Categoria criada. ID: {$id} - name: {$createdCategory->nameValue()}"
      );


      $this->repo->queryBuilder()->finishTransaction();

      return $createdCategory;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Category
  {
    return $this->repo->find($id);
  }

  public function bySlug(string $slug): ?Category
  {
    return $this->repo->forSlug($slug);
  }

  public function update(int $id, CategoryInputDTO $dto, array $fields): ?Category
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);

      $categoryToUpdate = $this->repo->find($id);
      if ($categoryToUpdate === null) {
        throw new Exception("Category not found", 500);
      }
      $category = $this->factory->fromDTO($dto);

      $categoryBySlug = $this->repo->forSlug($category->slugValue());
      if ($categoryBySlug !== null && $categoryToUpdate->slugValue() !== $categoryBySlug->slugValue()) {
        throw new CategorySlugAlreadyExists();
      }

      $updated = $this->repo->update($category, $fields);
      if (!$updated) {
        throw new CategoryUpdateExcepction();
      }

      $updatedCategory = $updated ? $this->repo->find($id) : null;
      if ($updatedCategory === null) {
        throw new CategoryUpdateExcepction();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Categoria atualizada ID: {$id} - nome: {$updatedCategory->nameValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedCategory;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function remove(int $id): bool
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $category = $this->repo->find($id);
      if ($category === null) {
        throw new Exception('Category not found.', 500);
      }

      $deleted = $this->repo->delete($category);
      if (!$deleted) {
        throw new CategoryRemoveException();
      }

      $this->eventLog->record(
        EventType::DELETE,
        "Categoria removida ID: {$id} - nome: {$category->nameValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $deleted;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}