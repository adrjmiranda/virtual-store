<?php

namespace App\Exceptions;

class InvalidRealException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente valores numéricos permitidos.")
  {
    parent::__construct($field, $message);
  }
}