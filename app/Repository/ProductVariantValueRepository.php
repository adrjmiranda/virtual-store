<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\ProductVariantValues\ProductVariantValue;
use App\Repository\Shared\BaseRepository;

class ProductVariantValueRepository extends BaseRepository
{
  private const string TABLE = 'product_variant_values';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, ProductVariantValue::class);
  }
}