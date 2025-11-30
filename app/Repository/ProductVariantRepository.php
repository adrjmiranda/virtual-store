<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\ProductVariants\ProductVariant;
use App\Repository\Shared\BaseRepository;

class ProductVariantRepository extends BaseRepository
{
  private const string TABLE = 'product_variants';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, ProductVariant::class);
  }
}