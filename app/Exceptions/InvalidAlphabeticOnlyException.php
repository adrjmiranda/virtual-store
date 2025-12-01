<?php

namespace App\Exceptions;

class InvalidAlphabeticOnlyException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras sem acento são permitidas.")
  {
    parent::__construct($field, $message);
  }
}