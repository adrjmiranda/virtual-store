<?php

namespace App\Exceptions;

class ProductVariantCreationException extends BusinessException
{
  public function __construct(string $field = "product_variant_create", string $message = "Erro ao tentar criar variante de produto.")
  {
    parent::__construct($field, $message);
  }
}