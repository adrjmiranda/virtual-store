<?php

namespace App\Exceptions;

class OrderCreationException extends BusinessException
{
  public function __construct(string $field = "order_create", string $message = "Erro ao tentar criar ordem.")
  {
    parent::__construct($field, $message);
  }
}