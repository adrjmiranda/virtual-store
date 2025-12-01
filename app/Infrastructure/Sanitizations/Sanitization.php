<?php

namespace App\Infrastructure\Sanitizations;

use App\Contracts\DTO\SanitizableDTO;
use InvalidArgumentException;
use ReflectionClass;

class Sanitization
{
  use Methods;

  private const string METHOD_SEPARATOR = '|';

  public function sanitize(SanitizableDTO $dto): SanitizableDTO
  {
    $rules = $dto->sanitizations();
    foreach ($rules as $field => $methodList) {
      if (empty($field)) {
        throw new InvalidArgumentException("The field passed to the sanitization list cannot be empty.", 500);
      }

      $methods = array_values(array_filter(explode(self::METHOD_SEPARATOR, $methodList)));

      if (empty($methods)) {
        throw new InvalidArgumentException("The list of methods passed to a field that needs to be sanitized cannot be empty.", 500);
      }

      foreach ($methods as $method) {
        if ($meethod === 'htmlspecialchars') {
          $dto->$field = htmlspecialchars($dto->$field, ENT_QUOTES, 'UTF-8');
        } else {
          $dto->$field = method_exists(self::class, $method) ? $this->$method($dto->$field) : $method($dto->$field);
        }
      }
    }

    return $dto;
  }
}