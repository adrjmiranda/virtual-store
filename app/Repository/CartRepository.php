<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Carts\Cart;
use App\Repository\Shared\BaseRepository;

class CartRepository extends BaseRepository
{
  private const string TABLE = 'carts';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Cart::class);
  }
}