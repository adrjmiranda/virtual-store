<?php

namespace App\Exceptions;

class ProductVariantRemoveException extends BusinessException
{
  public function __construct(string $field = "product_variant_remove", string $message = "Erro ao tentar remover variação de produto.")
  {
    parent::__construct($field, $message);
  }
}