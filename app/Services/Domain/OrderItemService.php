<?php

namespace App\Services\Domain;

use App\Factories\OrderItemFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\OrderItemRepository;

class OrderItemService
{
  public function __construct(
    private OrderItemRepository $repo,
    private OrderItemFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}