<?php

namespace App\Exceptions;

class ProductVariantUpdateException extends BusinessException
{
  public function __construct(string $field = "product_variant_update", string $message = "Erro ao tentar atualizar variante de produto.")
  {
    parent::__construct($field, $message);
  }
}