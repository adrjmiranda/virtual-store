<?php

namespace App\Domain\ValueObjects\Enum;

enum EvenType: string
{
  case LOGIN = 'login';
  case LOGOUT = 'logout';
  case CREATE = 'create';
  case UPDATED = 'update';
  case DELETE = 'delete';
  case PAYMENT = 'payment';
  case SYSTEM = 'system';
}