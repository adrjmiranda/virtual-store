<?php

namespace App\Exceptions;

class OrderRemoveException extends BusinessException
{
  public function __construct(string $field = "order_remove", string $message = "Erro ao tentar remover ordem.")
  {
    parent::__construct($field, $message);
  }
}