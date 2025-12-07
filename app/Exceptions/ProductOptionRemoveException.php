<?php

namespace App\Exceptions;

class ProductOptionRemoveException extends BusinessException
{
  public function __construct(string $field = "product_option_remove", string $message = "Erro ao tentar remover opção de produto.")
  {
    parent::__construct($field, $message);
  }
}