<?php

namespace App\Services\Domain;

use App\Factories\PaymentFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\PaymentRepository;

class PaymentService
{
  public function __construct(
    private PaymentRepository $repo,
    private PaymentFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}