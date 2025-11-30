<?php

namespace App\Domain\Images;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class Image extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private Path $path,
    private ImageableId $imageableId,
    private ImageableType $imageableType,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}