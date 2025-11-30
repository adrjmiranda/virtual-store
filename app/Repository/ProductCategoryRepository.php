<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\ProductCategory\ProductCategory;
use App\Repository\Shared\BaseRepository;

class ProductCategoryRepository extends BaseRepository
{
  private const string TABLE = 'product_category';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, ProductCategory::class);
  }
}