<?php

namespace App\Factories;

use App\Domain\Categories\Category;
use App\Domain\Categories\ParentId;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Slug;
use App\DTO\CategoryInputDTO;

class CategoryFactory
{
  public function fromDTO(CategoryInputDTO $dto, ?Category $category = null): Category
  {
    return new Category(
      id: $category?->id(),
      name: $dto->name === null ? $category?->name() : new Name($dto->name),
      slug: $dto->slug === null ? $category?->slug() : new Slug($dto->slug),
      description: $dto->description === null ? $category?->description() : new Description($dto->description),
      parentId: $dto->parentId === null ? $category?->parentId() : new ParentId($dto->parentId),
      isActive: $dto->isActive === null ? $category?->isActive() : new IsActive($dto->isActive),
      createdAt: null,
      updatedAt: null
    );
  }
}