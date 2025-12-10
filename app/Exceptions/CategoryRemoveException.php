<?php

namespace App\Exceptions;

class CategoryRemoveException extends BusinessException
{
  public function __construct(string $field = "category_remove", string $message = "Erro ao tentar remover categoria.")
  {
    parent::__construct($field, $message);
  }
}