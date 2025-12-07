<?php

namespace App\Exceptions;

class ProductRemoveException extends BusinessException
{
  public function __construct(string $field = "product_remove", string $message = "Erro ao tentar remover produto.")
  {
    parent::__construct($field, $message);
  }
}