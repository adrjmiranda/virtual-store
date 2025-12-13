<?php

namespace App\Factories;

use App\Domain\Coupons\Code;
use App\Domain\Coupons\Coupon;
use App\Domain\Coupons\ExpiresAt;
use App\Domain\Coupons\StartsAt;
use App\Domain\Coupons\Type;
use App\Domain\Coupons\UsageLimit;
use App\Domain\Coupons\UsedCount;
use App\Domain\Coupons\Value;
use App\Domain\ValueObjects\Enum\CouponType;
use App\DTO\CouponInputDTO;

class CouponFactory
{
  public function fromDTO(CouponInputDTO $dto, ?Coupon $coupon = null): ?Coupon
  {
    return new Coupon(
      id: $coupon?->id(),
      code: $dto->code === null ? $coupon?->code() : new Code($dto->code),
      type: $dto->type === null ? $coupon?->type() : new Type(CouponType::from($dto->type)),
      value: $dto->value === null ? $coupon?->value() : new Value($dto->value),
      usageLimit: $dto->usageLimit === null ? $coupon?->usageLimit() : new UsageLimit($dto->usageLimit),
      usedCount: $dto->usedCount === null ? $coupon?->usedCount() : new UsedCount($dto->usedCount),
      startsAt: $dto->startsAt === null ? $coupon?->startsAt() : new StartsAt($dto->startsAt),
      expiresAt: $dto->expiresAt === null ? $coupon?->expiresAt() : new ExpiresAt($dto->expiresAt),
      createdAt: null,
      updatedAt: null
    );
  }
}