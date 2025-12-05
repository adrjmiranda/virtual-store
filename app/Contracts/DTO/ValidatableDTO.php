<?php

namespace App\Contracts\DTO;

interface ValidatableDTO
{
  public function validations(): array;
}