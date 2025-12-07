<?php

namespace App\Exceptions;

class ProductUpdateException extends BusinessException
{
  public function __construct(string $field = "product_update", string $message = "Erro ao tentar atualizar produto.")
  {
    parent::__construct($field, $message);
  }
}