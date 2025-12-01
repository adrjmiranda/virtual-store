<?php

namespace App\Exceptions;

class InvalidAlphabeticWithAccentsException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras são perimtidas.")
  {
    parent::__construct($field, $message);
  }
}