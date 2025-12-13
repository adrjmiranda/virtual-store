<?php

namespace App\Services\Domain;

use App\Domain\Coupons\Coupon;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CouponInputDTO;
use App\Exceptions\CouponCreationException;
use App\Factories\CouponFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CouponRepository;
use App\Repository\EventLogRepository;

class CouponService
{
  public function __construct(
    private CouponRepository $repo,
    private CouponFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }

  public function create(CouponInputDTO $dto): ?Coupon
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $coupon = $this->factory->fromDTO($dto);
      $id = $this->repo->insert($coupon, Coupon::FIELDS_INSERT);

      if ($id === null) {
        throw new CouponCreationException();
      }

      $createdCoupon = $this->repo->find($id);
      if ($createdCoupon === null) {
        throw new CouponCreationException();
      }

      $this->eventLog->record(
        EventType::CREATE,
        "Coupon criado ID {$createdCoupon->idValue()} CODE {$createdCoupon->codeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $createdCoupon;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }

  public function show(int $id): ?Coupon
  {
    return $this->repo->find($id);
  }
}