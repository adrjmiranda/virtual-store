<?php

namespace App\Exceptions;

class InvalidAlphabeticWithHifenException extends BusinessException
{
  public function __construct(string $field, string $message = "Somente letras e hifens são permitidos.")
  {
    parent::__construct($field, $message);
  }
}