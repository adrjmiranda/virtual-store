<?php

namespace App\Exceptions;

class ProductOptionValueRemoveException extends BusinessException
{
  public function __construct(string $field = "product_option_value_remove", string $message = "Erro ao tentar remover opção e valor de produto.")
  {
    parent::__construct($field, $message);
  }
}