<?php

namespace App\Exceptions;

class OrderItemUpdateException extends BusinessException
{
  public function __construct(string $field = "order_item_update", string $message = "Erro ao tentar atualizar item de ordem.")
  {
    parent::__construct($field, $message);
  }
}