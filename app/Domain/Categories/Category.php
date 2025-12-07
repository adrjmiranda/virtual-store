<?php

namespace App\Domain\Categories;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\Slug;
use App\Domain\ValueObjects\UpdatedAt;

class Category extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'name',
    'slug',
    'description',
    'parent_id',
    'is_active'
  ];


  public function __construct(
    private ?Id $id,
    private Name $name,
    private Slug $slug,
    private ?Description $description,
    private ?ParentId $parentId,
    private IsActive $isActive,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}