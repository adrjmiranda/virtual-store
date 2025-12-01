<?php

namespace App\Exceptions;

class RequiredFieldException extends BusinessException
{
  public function __construct(string $field, string $message = "Este campo é obrigatório.")
  {
    parent::__construct($field, $message);
  }
}