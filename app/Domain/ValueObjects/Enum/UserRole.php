<?php

namespace App\Domain\ValueObjects\Enum;

enum UserRole: string
{
  case USER = 'user';
  case ADMIN = 'admin';
  case MANAGER = 'manager';
}