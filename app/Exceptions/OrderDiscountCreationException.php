<?php

namespace App\Exceptions;

class OrderDiscountCreationException extends BusinessException
{
  public function __construct(string $field = "order_discount_create", string $message = "Erro ao tentar criar desconto para ordem.")
  {
    parent::__construct($field, $message);
  }
}