<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Categories\Category;
use App\Repository\Shared\BaseRepository;

class CategoryRepository extends BaseRepository
{
  private const string TABLE = 'categories';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Category::class);
  }

  public function forSlug(string $slug): ?Category
  {
    $row = $this->queryBuilder
      ->from($this->table)
      ->andWhere('slug', '=', $slug)
      ->first();

    return $row ? Category::fromDatabase($row) : null;
  }
}