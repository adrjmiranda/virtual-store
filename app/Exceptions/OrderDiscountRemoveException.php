<?php

namespace App\Exceptions;

class OrderDiscountRemoveException extends BusinessException
{
  public function __construct(string $field = "order_discount_remove", string $message = "Erro ao tentar remover desconto para ordem.")
  {
    parent::__construct($field, $message);
  }
}