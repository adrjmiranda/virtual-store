<?php

namespace App\DTO;

abstract class BaseDTO
{
  protected string $fieldPrefix = '';

  public function fieldPrefix(): string
  {
    return "{$this->fieldPrefix}_";
  }
}