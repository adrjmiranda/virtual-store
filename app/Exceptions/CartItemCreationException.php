<?php

namespace App\Exceptions;

class CartItemCreationException extends BusinessException
{
  public function __construct(string $field = "cart_item_create", string $message = "Erro ao tentar criar item do carrinho.")
  {
    parent::__construct($field, $message);
  }
}