<?php

namespace App\Exceptions;

class ProductCreationException extends BusinessException
{
  public function __construct(string $field = "product_create", string $message = "Erro ao tentar criar produto.")
  {
    parent::__construct($field, $message);
  }
}