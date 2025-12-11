<?php

namespace App\Exceptions;

class CartItemRemoveException extends BusinessException
{
  public function __construct(string $field = "cart_item_remove", string $message = "Erro ao tentar remover item do carrinho.")
  {
    parent::__construct($field, $message);
  }
}