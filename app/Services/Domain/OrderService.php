<?php

namespace App\Services\Domain;

use App\Factories\OrderFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderRepository;

class OrderService
{
  public function __construct(
    private OrderRepository $repo,
    private OrderFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}