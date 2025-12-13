<?php

namespace App\Services\Domain;

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
}