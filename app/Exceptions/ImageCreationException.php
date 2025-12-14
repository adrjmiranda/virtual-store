<?php

namespace App\Exceptions;

class ImageCreationException extends BusinessException
{
  public function __construct(string $field = "image_create", string $message = "Erro ao tentar criar imagem.")
  {
    parent::__construct($field, $message);
  }
}