<?php

namespace App\Exceptions;

class InvalidAlphabeticWithSpacesException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras e espaços são permitidos.")
  {
    parent::__construct($field, $message);
  }
}