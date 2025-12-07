<?php

namespace App\Exceptions;

class ProductOptionUpdateException extends BusinessException
{
  public function __construct(string $field = "product_option_update", string $message = "Erro ao tentar atualizar opção de produto.")
  {
    parent::__construct($field, $message);
  }
}