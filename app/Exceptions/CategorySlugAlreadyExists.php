<?php

namespace App\Exceptions;

class CategorySlugAlreadyExists extends BusinessException
{
  public function __construct(string $field = 'product_slug', string $message = "Já existe uma categoria registrada com esse slug")
  {
    parent::__construct($field, $message);
  }
}