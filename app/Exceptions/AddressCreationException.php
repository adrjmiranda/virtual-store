<?php

namespace App\Exceptions;

class AddressCreationException extends BusinessException
{
  public function __construct(string $field = "address_create", string $message = "Error ao tentar criar endereço.")
  {
    parent::__construct($field, $message);
  }
}