<?php

namespace App\Exceptions;

class InvalidSlugException extends BusinessException
{
  public function __construct(string $field, string $message = "Slug inválido.")
  {
    parent::__construct($field, $message);
  }
}