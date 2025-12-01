<?php

namespace App\Infrastructure\Sanitizations;

use App\Contracts\DTO\SanitizableDTO;
use InvalidArgumentException;

class Sanitization
{
  use Methods;

  private const string METHOD_SEPARATOR = '|';

  public function sanitize(SanitizableDTO $dto): SanitizableDTO
  {
    $rules = $dto->sanitizations();

    foreach ($rules as $field => $methodList) {
      if (empty($field)) {
        throw new InvalidArgumentException("Sanitization field cannot be empty.", 500);
      }

      if (!property_exists($dto, $field)) {
        throw new InvalidArgumentException("Field '{$field}' does not exist on DTO.", 500);
      }

      $methods = array_values(array_filter(explode(self::METHOD_SEPARATOR, $methodList)));

      if (empty($methods)) {
        throw new InvalidArgumentException("Sanitization method list cannot be empty.", 500);
      }

      foreach ($methods as $method) {
        if ($dto->$field === null || $dto->$field === '') {
          continue;
        }

        if ($method === 'htmlspecialchars') {
          $dto->$field = htmlspecialchars($dto->$field, ENT_QUOTES, 'UTF-8');
          continue;
        }

        if (method_exists($this, $method)) {
          $dto->$field = $this->$method($dto->$field);
          continue;
        }

        $allowedNativeFunctions = [
          'trim',
          'ltrim',
          'rtrim',
          'strtolower',
          'strtoupper',
          'ucwords',
          'addslashes',
          'stripslashes'
        ];

        if (\in_array($method, $allowedNativeFunctions)) {
          $dto->$field = $method($dto->$field);
          continue;
        }

        throw new InvalidArgumentException("Sanitization method '{$method}' is not allowed.", 500);
      }
    }

    return $dto;
  }
}