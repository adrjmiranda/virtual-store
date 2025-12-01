<?php

namespace App\Exceptions;

class InvalidEmailException extends BusinessException
{
  public function __construct(string $field, string $message = "Endereço de email inválido.")
  {
    parent::__construct($field, $message);
  }
}