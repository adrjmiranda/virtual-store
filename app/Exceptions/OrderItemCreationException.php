<?php

namespace App\Exceptions;

class OrderItemCreationException extends BusinessException
{
  public function __construct(string $field = "order_item_create", string $message = "Erro ao tentar criar item de ordem.")
  {
    parent::__construct($field, $message);
  }
}