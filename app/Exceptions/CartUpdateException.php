<?php

namespace App\Exceptions;

class CartUpdateException extends BusinessException
{
  public function __construct(string $field = "cart_update", string $message = "Erro ao tentar atualizar carrinho.")
  {
    parent::__construct($field, $message);
  }
}