<?php

namespace App\Services\Domain;

use App\Domain\Coupons\Coupon;
use App\Domain\ValueObjects\Enum\EventType;
use App\DTO\CouponInputDTO;
use App\Exceptions\CouponCreationException;
use App\Exceptions\CouponUpdateException;
use App\Factories\CouponFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CouponRepository;
use App\Repository\EventLogRepository;
use Exception;

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

  public function update(int $id, CouponInputDTO $dto, array $fields): ?Coupon
  {
    try {
      $this->repo->queryBuilder()->startTransaction();
      $dto = $this->s->sanitize($dto);
      $this->v->validate($dto);
      $couponToUpdate = $this->repo->find($id);
      if ($couponToUpdate === null) {
        throw new Exception('Coupon not found.', 500);
      }
      $coupon = $this->factory->fromDTO($dto);
      $updated = $this->repo->update($coupon, $fields);

      if (!$updated) {
        throw new CouponUpdateException();
      }

      $updatedCoupon = $updated ? $this->repo->find($id) : null;
      if ($updatedCoupon === null) {
        throw new CouponUpdateException();
      }

      $this->eventLog->record(
        EventType::UPDATED,
        "Coupon atualizado ID {$updatedCoupon->idValue()} CODE {$updatedCoupon->codeValue()}"
      );

      $this->repo->queryBuilder()->finishTransaction();

      return $updatedCoupon;
    } catch (\Throwable $th) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $th;
    }
  }
}