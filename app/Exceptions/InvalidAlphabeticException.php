<?php

namespace App\Exceptions;

class InvalidAlphabeticException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras, espaços e hífens são permitidos.")
  {
    parent::__construct($field, $message);
  }
}