<?php

namespace App\Exceptions;

class UserRemoveException extends BusinessException
{
  public function __construct(string $field = "user_remove", string $message = "Error ao tentar remover usuário.")
  {
    parent::__construct($field, $message);
  }
}