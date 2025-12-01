<?php

namespace App\Exceptions;

class InvalidPositiveIntegerException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente números inteiros positivos são permitidos.")
  {
    parent::__construct($field, $message);
  }
}