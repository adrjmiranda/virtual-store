<?php

namespace App\Exceptions;

class InvalidRoleException extends BusinessException
{
  public function __construct(string $field, string $message = "O usuário só pode ser: admin, user ou manager")
  {
    parent::__construct($field, $message);
  }
}