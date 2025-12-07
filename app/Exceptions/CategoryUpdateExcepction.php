<?php

namespace App\Exceptions;

class CategoryUpdateExcepction extends BusinessException
{
  public function __construct(string $field = "user_update", string $message = "Erro ao tentar atualizar categoria.")
  {
    parent::__construct($field, $message);
  }
}