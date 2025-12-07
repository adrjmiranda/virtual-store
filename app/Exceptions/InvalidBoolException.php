<?php

namespace App\Exceptions;

class InvalidBoolException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente valores boleanos permitidos.")
  {
    parent::__construct($field, $message);
  }
}