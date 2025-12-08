<?php

namespace App\Exceptions;

class ProductVariantValueCreationException extends BusinessException
{
  public function __construct(string $field = "product_variant_value_create", string $message = "Erro ao tentar criar variante e valor de produto.")
  {
    parent::__construct($field, $message);
  }
}