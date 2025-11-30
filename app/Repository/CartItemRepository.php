<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\CartItems\CartItem;
use App\Repository\Shared\BaseRepository;

class CartItemRepository extends BaseRepository
{
  private const string TABLE = 'cart_items';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, CartItem::class);
  }
}