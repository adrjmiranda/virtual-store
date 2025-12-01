<?php

namespace App\Exceptions;

class InvalidMinLenException extends BusinessException
{
  public function __construct(string $field, string $message)
  {
    parent::__construct($field, $message);
  }
}