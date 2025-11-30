<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\OrderItems\OrderItem;
use App\Repository\Shared\BaseRepository;

class OrderItemRepository extends BaseRepository
{
  private const string TABLE = 'order_items';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, OrderItem::class);
  }
}