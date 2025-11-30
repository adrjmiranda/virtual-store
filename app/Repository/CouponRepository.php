<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Coupons\Coupon;
use App\Repository\Shared\BaseRepository;

class CouponRepository extends BaseRepository
{
  private const string TABLE = 'coupons';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Coupon::class);
  }
}