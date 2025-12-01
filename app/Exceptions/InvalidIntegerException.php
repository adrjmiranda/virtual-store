<?php

namespace App\Exceptions;

class InvalidIntegerException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente números inteiros permitidos.")
  {
    parent::__construct($field, $message);
  }
}