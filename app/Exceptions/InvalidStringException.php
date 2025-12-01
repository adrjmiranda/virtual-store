<?php

namespace App\Exceptions;

class InvalidStringException extends BusinessException
{
  public function __construct(string $field, string $message = "O campo contém caracteres que não são permitidos.")
  {
    parent::__construct($field, $message);
  }
}