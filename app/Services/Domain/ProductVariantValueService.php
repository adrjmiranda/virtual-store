<?php

namespace App\Services\Domain;

use App\Factories\ProductVariantValueFactory;
use App\Infrastructure\Sanitizations\Sanitization;
use App\Infrastructure\Validations\Validation;
use App\Repository\EventLogRepository;
use App\Repository\ProductVariantValueRepository;

class ProductVariantValueService
{
  public function __construct(
    private ProductVariantValueRepository $repo,
    private ProductVariantValueFactory $factory,
    private Validation $v,
    private Sanitization $s,
    private EventLogRepository $eventLog
  ) {
  }
}