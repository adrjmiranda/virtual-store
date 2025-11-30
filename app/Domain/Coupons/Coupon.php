<?php

namespace App\Domain\Coupons;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class Coupon extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private Code $code,
    private Type $type,
    private Value $value,
    private ?UsageLimit $usageLimit,
    private UsedCount $usedCount,
    private StartsAt $startsAt,
    private ?ExpiresAt $expiresAt,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}