<?php

namespace App\Exceptions;

class CategoryCreationException extends BusinessException
{
  public function __construct(string $field = "category_create", string $message = "Erro ao tentar criar categoria.")
  {
    parent::__construct($field, $message);
  }
}