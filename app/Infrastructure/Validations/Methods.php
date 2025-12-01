<?php

namespace App\Infrastructure\Validations;

use App\Exceptions\InvalidAlphabeticException;
use App\Exceptions\InvalidAlphabeticOnlyException;
use App\Exceptions\InvalidAlphabeticWithAccentsException;
use App\Exceptions\InvalidAlphabeticWithHifenException;
use App\Exceptions\InvalidAlphabeticWithSpacesException;
use App\Exceptions\InvalidIntegerException;
use App\Exceptions\InvalidMaxLenException;
use App\Exceptions\InvalidMinLenException;
use App\Exceptions\InvalidNegativeIntegerException;
use App\Exceptions\InvalidPositiveIntegerException;
use App\Exceptions\RequiredFieldException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidNumericException;
use InvalidArgumentException;

trait Methods
{
  private function required(string $field, mixed $value): void
  {
    if ($value === null || $value === '') {
      throw new RequiredFieldException($field);
    }
  }

  private function email(string $field, string $value): void
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidEmailException($field);
    }
  }

  private function numeric(string $field, float|int|string $value): void
  {

    if (\is_string($value) && !preg_match('/^-?((0|[1-9][0-9]*)(\.[0-9]+)?|\.[0-9]+)$/', $value)) {
      throw new InvalidNumericException($field);
    }

    if (!\is_int($value) && !\is_float($value)) {
      throw new InvalidNumericException($field);
    }
  }

  private function int(string $field, int|string $value): void
  {

    if (\is_string($value) && !preg_match('/^(0|(-?[1-9][0-9]*))$/', $value)) {
      throw new InvalidIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidIntegerException($field);
    }
  }

  private function positive(string $field, int|string $value): void
  {
    if (\is_string($value) && !preg_match('/^[1-9][0-9]*$/', $value)) {
      throw new InvalidPositiveIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidPositiveIntegerException($field);
    }
  }

  private function negative(string $field, int|string $value): void
  {
    if (\is_string($value) && !preg_match('/^-[1-9][0-9]*$/', $value)) {
      throw new InvalidNegativeIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidNegativeIntegerException($field);
    }
  }

  private function alpha(string $field, string $value): void
  {
    if (!preg_match('/^[A-Za-z]+$/', $value)) {
      throw new InvalidAlphabeticOnlyException($field);
    }
  }

  private function alphaaccents(string $field, string $value): void
  {
    if (!preg_match('/^[\p{L}]+$/u', $value)) {
      throw new InvalidAlphabeticWithAccentsException($field);
    }
  }

  private function alphaspaces(string $field, string $value): void
  {
    if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
      throw new InvalidAlphabeticWithSpacesException($field);
    }
  }

  private function alphahifen(string $field, string $value): void
  {
    if (!preg_match('/^[\p{L}-]+$/u', $value)) {
      throw new InvalidAlphabeticWithHifenException($field);
    }
  }

  private function alphabetic(string $field, string $value): void
  {
    if (!preg_match('/^[\p{L}\s-]+$/u', $value)) {
      throw new InvalidAlphabeticException($field);
    }
  }

  private function min(string $field, string $value, array $params): void
  {
    $minLen = $params[0] ?? null;
    if ($minLen === null) {
      throw new InvalidArgumentException("The min validation function requires a parameter.", 500);
    }

    if (!preg_match('/^[1-9][0-9]*$/', $value)) {
      throw new InvalidArgumentException("The min validation function requires a positive integer parameter.", 500);
    }

    $minLen = (int) $minLen;
    if (\strlen($value) < $minLen) {
      throw new InvalidMinLenException($field, "O campo deve ter no mínimo {$minLen} caracteres.");
    }
  }

  private function max(string $field, string $value, array $params): void
  {
    $maxLen = $params[0] ?? null;
    if ($maxLen === null) {
      throw new InvalidArgumentException("The min validation function requires a parameter.", 500);
    }

    if (!preg_match('/^[1-9][0-9]*$/', $value)) {
      throw new InvalidArgumentException("The min validation function requires a positive integer parameter.", 500);
    }

    $maxLen = (int) $maxLen;
    if (\strlen($value) > $maxLen) {
      throw new InvalidMaxLenException($field, "O campo deve ter no máximo {$maxLen} caracteres.");
    }
  }
}