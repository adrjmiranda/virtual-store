<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\ProductOptionValues\ProductOptionValue;
use App\Repository\Shared\BaseRepository;

class ProductOptionValueRepository extends BaseRepository
{
  private const string TABLE = 'product_option_values';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, ProductOptionValue::class);
  }
}