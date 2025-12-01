<?php

namespace App\Exceptions;

class InvalidMaxLenException extends BusinessException
{
  public function __construct(string $field, string $message)
  {
    parent::__construct($field, $message);
  }
}