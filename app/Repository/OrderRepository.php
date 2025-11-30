<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Orders\Order;
use App\Repository\Shared\BaseRepository;

class OrderRepository extends BaseRepository
{
  private const string TABLE = 'orders';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Order::class);
  }
}