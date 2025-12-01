<?php

namespace App\Exceptions;

class InvalidAlphaNumericException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras e números permitidos.")
  {
    parent::__construct($field, $message);
  }
}