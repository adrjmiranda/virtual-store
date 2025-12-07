<?php

namespace App\Exceptions;

class ProductOptionCreationException extends BusinessException
{
  public function __construct(string $field = "product_option_create", string $message = "Erro ao tentar criar opção de produto.")
  {
    parent::__construct($field, $message);
  }
}