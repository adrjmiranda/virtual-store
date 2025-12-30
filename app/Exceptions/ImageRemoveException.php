<?php

namespace App\Exceptions;

class ImageRemoveException extends BusinessException
{
  public function __construct(string $field = "image_remove", string $message = "Erro ao tentar remover imagem.")
  {
    parent::__construct($field, $message);
  }
}