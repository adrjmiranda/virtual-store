<?php

namespace App\Exceptions;

class ProductOptionValueCreationException extends BusinessException
{
  public function __construct(string $field = "product_option_value_create", string $message = "Erro ao tentar criar opção e valor de produto.")
  {
    parent::__construct($field, $message);
  }
}