<?php

namespace App\Exceptions;

class ProductOptionValueUpdateException extends BusinessException
{
  public function __construct(string $field = "product_option_value_update", string $message = "Erro ao tentar atualizar opção e valor de produto.")
  {
    parent::__construct($field, $message);
  }
}