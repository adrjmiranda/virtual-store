<?php

namespace App\Domain\Products;

use App\Domain\ProductVariants\Stock;
use App\Domain\Users\DeletedAt;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\IsActive;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\Price;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\Slug;
use App\Domain\ValueObjects\UpdatedAt;

class Product extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private Name $name,
    private Slug $slug,
    private ?Description $description,
    private Price $price,
    private Stock $stock,
    private IsActive $isActive,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt,
    private ?DeletedAt $deletedAt
  ) {
  }
}