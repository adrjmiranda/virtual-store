<?php

namespace App\Exceptions;

class UserCreationException extends BusinessException
{
  public function __construct(string $field = "user_create", string $message = "Erro ao tentar criar usuário.")
  {
    parent::__construct($field, $message);
  }
}