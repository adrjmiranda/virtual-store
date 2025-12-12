<?php

namespace App\Exceptions;

class CartCreationException extends BusinessException
{
  public function __construct(string $field = "cart_create", string $message = "Erro ao tentar criar carrinho.")
  {
    parent::__construct($field, $message);
  }
}