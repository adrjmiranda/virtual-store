<?php

namespace App\Exceptions;

class OrderDiscountUpdateException extends BusinessException
{
  public function __construct(string $field = "order_discount_update", string $message = "Erro ao tentar atualizar desconto para ordem.")
  {
    parent::__construct($field, $message);
  }
}