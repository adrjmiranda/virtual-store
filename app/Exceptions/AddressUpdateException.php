<?php

namespace App\Exceptions;

class AddressUpdateException extends BusinessException
{
  public function __construct(string $field = "address_update", string $message = "Error ao tentar atualizar endereço.")
  {
    parent::__construct($field, $message);
  }
}