<?php

namespace App\Services\Domain;

use App\Factories\CartItemFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CartItemRepository;
use App\Repository\EventLogRepository;

class CartItemService
{
  public function __construct(
    private CartItemRepository $repo,
    private CartItemFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}