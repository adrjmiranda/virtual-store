<?php

namespace App\Services\Domain;

use App\Factories\ProductVariantFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductVariantRepository;

class ProductVariantService
{
  public function __construct(
    private ProductVariantRepository $repo,
    private ProductVariantFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}