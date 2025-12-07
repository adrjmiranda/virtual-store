<?php

namespace App\Exceptions;

class ProductSlugAlreadyExists extends BusinessException
{
  public function __construct(string $field = 'product_slug', string $message = "Já existe um produto registrado com esse slug")
  {
    parent::__construct($field, $message);
  }
}