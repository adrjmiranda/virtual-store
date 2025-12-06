<?php

namespace App\Exceptions;

class UserAlreadyExistsException extends BusinessException
{
  public function __construct(string $field = 'user_email', string $message = "Esse e-mail já está registrado no sistema.")
  {
    parent::__construct($field, $message);
  }
}