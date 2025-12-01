<?php

namespace App\Contracts\DTO;

interface SanitizableDTO
{
  public function sanitizations(): array;
}