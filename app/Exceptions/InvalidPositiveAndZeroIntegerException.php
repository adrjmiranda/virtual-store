<?php

namespace App\Exceptions;

class InvalidPositiveAndZeroIntegerException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente números positivos ou zero.")
  {
    parent::__construct($field, $message);
  }
}