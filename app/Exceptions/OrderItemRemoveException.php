<?php

namespace App\Exceptions;

class OrderItemRemoveException extends BusinessException
{
  public function __construct(string $field = "order_item_remove", string $message = "Erro ao tentar remover item de ordem.")
  {
    parent::__construct($field, $message);
  }
}