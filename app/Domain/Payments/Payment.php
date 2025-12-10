<?php

namespace App\Domain\Payments;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class Payment extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'user_id',
    'amount',
    'method',
    'status',
    'transaction_id',
    'paid_at',
  ];

  public function __construct(
    private ?Id $id,
    private UserId $userId,
    private Amount $amount,
    private Method $method,
    private Status $status,
    private ?TransactionId $transactionId,
    private ?PaidAt $paidAt,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}