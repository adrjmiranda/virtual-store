<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\OrderPayments\OrderPayment;
use App\Repository\Shared\BaseRepository;

class OrderPaymentRepository extends BaseRepository
{
  private const string TABLE = 'order_payments';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, OrderPayment::class);
  }
}