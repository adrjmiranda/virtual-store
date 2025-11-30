<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\OrderDiscounts\OrderDiscount;
use App\Repository\Shared\BaseRepository;

class OrderDiscountRepository extends BaseRepository
{
  private const string TABLE = 'order_discounts';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, OrderDiscount::class);
  }
}