<?php

namespace App\Services\Domain;

use App\Repository\ProductVariantRepository;

class ProductVariantService
{
  public function __construct(
    private ProductVariantRepository $repo,
  ) {
  }
}