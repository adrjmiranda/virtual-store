<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Payments\Payment;
use App\Repository\Shared\BaseRepository;

class PaymentRepository extends BaseRepository
{
  private const string TABLE = 'payments';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Payment::class);
  }
}