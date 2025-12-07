<?php

namespace App\Services\Domain;

use App\Domain\Categories\Category;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CategoryInputDTO;
use App\Exceptions\CategoryCreationException;
use App\Exceptions\CategorySlugAlreadyExists;
use App\Factories\CategoryFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CategoryRepository;
use App\Repository\EventLogRepository;

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
}