<?php

namespace App\Exceptions;

class ImageUpdateException extends BusinessException
{
  public function __construct(string $field = "image_update", string $message = "Erro ao tentar atualizar imagem.")
  {
    parent::__construct($field, $message);
  }
}