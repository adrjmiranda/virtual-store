<?php

namespace App\Contracts\DTO;

interface ValidatableDTO
{
  public function fieldPrefix(): string;
  public function validations(): array;
}