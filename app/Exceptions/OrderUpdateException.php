<?php

namespace App\Exceptions;

class OrderUpdateException extends BusinessException
{
  public function __construct(string $field = "order_update", string $message = "Erro ao tentar atualizar ordem.")
  {
    parent::__construct($field, $message);
  }
}