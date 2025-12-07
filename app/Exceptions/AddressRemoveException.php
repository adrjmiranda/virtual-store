<?php

namespace App\Exceptions;

class AddressRemoveException extends BusinessException
{
  public function __construct(string $field = "address_remove", string $message = "Erro ao tentar remover endereço.")
  {
    parent::__construct($field, $message);
  }
}