<?php

namespace App\Services\Domain;

use App\Factories\CartFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\CartRepository;
use App\Repository\EventLogRepository;

class CartService
{
  public function __construct(
    private CartRepository $repo,
    private CartFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}