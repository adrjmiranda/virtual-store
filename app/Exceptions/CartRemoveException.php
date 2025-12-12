<?php

namespace App\Exceptions;

class CartRemoveException extends BusinessException
{
  public function __construct(string $field = "cart_remove", string $message = "Erro ao tentar remover carrinho.")
  {
    parent::__construct($field, $message);
  }
}