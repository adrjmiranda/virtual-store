<?php

namespace App\Exceptions;

use Throwable;

class AddressCreationException extends BusinessException
{
  public function __construct(string $field = "address_create", string $message = "Error ao criar endereço.")
  {
    parent::__construct($field, $message);
  }
}