<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\ProductOptions\ProductOption;
use App\Repository\Shared\BaseRepository;

class ProductOptionRepository extends BaseRepository
{
  private const string TABLE = 'product_options';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, ProductOption::class);
  }
}