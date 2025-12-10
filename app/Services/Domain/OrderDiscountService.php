<?php

namespace App\Services\Domain;

use App\Factories\OrderDiscountFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderDiscountRepository;

class OrderDiscountService
{
  public function __construct(
    private OrderDiscountRepository $repo,
    private OrderDiscountFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}