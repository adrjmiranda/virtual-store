<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Products\Product;
use App\Repository\Shared\BaseRepository;

class ProductRepository extends BaseRepository
{
  private const string TABLE = 'products';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Product::class);
  }

  public function forSlug(string $slug): ?Product
  {
    $row = $this->queryBuilder
      ->from($this->table)
      ->andWhere('slug', '=', $slug)
      ->first();

    return $row ? Product::fromDatabase($row) : null;
  }
}