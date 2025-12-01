<?php

namespace App\Exceptions;

class InvalidNegativeIntegerException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente números inteiros negativos são permitidos.")
  {
    parent::__construct($field, $message);
  }
}