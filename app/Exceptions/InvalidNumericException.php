<?php

namespace App\Exceptions;

class InvalidNumericException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente caracteres numéricos permitidos.")
  {
    parent::__construct($field, $message);
  }
}