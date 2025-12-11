<?php

namespace App\Exceptions;

class CartItemUpdateException extends BusinessException
{
  public function __construct(string $field = "cart_item_update", string $message = "Erro ao tentar atualizar item do carrinho.")
  {
    parent::__construct($field, $message);
  }
}