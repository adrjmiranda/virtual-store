<?php

namespace App\Exceptions;

class InvalidDateException extends BusinessException
{
  public function __construct(string $field, string $message = "Data inválida.")
  {
    parent::__construct($field, $message);
  }
}