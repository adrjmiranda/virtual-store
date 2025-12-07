<?php

namespace App\Exceptions;

class UserUpdateExcepction extends BusinessException
{
  public function __construct(string $field = "user_update", string $message = "Erro ao tentar atualizar usuário.")
  {
    parent::__construct($field, $message);
  }
}