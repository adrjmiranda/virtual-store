<?php

namespace App\Infrastructure\Validations;

use App\Contracts\DTO\ValidatableDTO;
use InvalidArgumentException;

class Validation
{
  use Methods;

  private const string RULE_METHOD_SEPARATOR = '|';
  private const string RULE_METHOD_PARAM_SEPARATOR = '@';

  private function ruleMethodList(string $ruleList): array
  {
    return array_values(array_filter(explode(self::RULE_METHOD_SEPARATOR, $ruleList)));
  }

  private function methodAndParams(string $rule): array
  {
    if (str_contains($rule, self::RULE_METHOD_PARAM_SEPARATOR)) {
      $parts = explode(self::RULE_METHOD_PARAM_SEPARATOR, $rule);
      if (empty($parts[0])) {
        throw new InvalidArgumentException("Methods passed to validation rules must not be empty.", 500);
      }

      $params = array_values(array_filter(\array_slice($parts, 1)));
      if (empty($params)) {
        throw new InvalidArgumentException("The value of a parameter passed to a validation method must not be empty.", 500);
      }

      foreach ($params as $value) {
        if (empty($value)) {
          throw new InvalidArgumentException("The value of a parameter passed to a validation method must not be empty.", 500);
        }
      }

      return [$parts[0], $params];
    }

    return [$rule, []];
  }

  public function validate(ValidatableDTO $dto): void
  {
    $rules = $dto->validations();
    foreach ($rules as $field => $ruleList) {
      if (empty($field)) {
        throw new InvalidArgumentException("The field passed to the validation list cannot be empty.", 500);
      }

      $ruleMethods = $this->ruleMethodList($ruleList);
      if (empty($ruleMethods)) {
        throw new InvalidArgumentException("Methods passed to validation rules must not be empty.", 500);
      }

      foreach ($ruleMethods as $rule) {
        [$method, $params] = $this->methodAndParams($rule);
        $this->$method($dto->fieldPrefix() . camelToSnake($field), $dto->$field, $params);
      }
    }
  }
}