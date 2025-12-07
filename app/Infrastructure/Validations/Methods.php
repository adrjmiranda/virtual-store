<?php

namespace App\Infrastructure\Validations;

use App\Exceptions\InvalidAlphabeticException;
use App\Exceptions\InvalidAlphabeticOnlyException;
use App\Exceptions\InvalidAlphabeticWithAccentsException;
use App\Exceptions\InvalidAlphabeticWithHifenException;
use App\Exceptions\InvalidAlphabeticWithSpacesException;
use App\Exceptions\InvalidAlphaNumericException;
use App\Exceptions\InvalidBoolException;
use App\Exceptions\InvalidDateException;
use App\Exceptions\InvalidIntegerException;
use App\Exceptions\InvalidMaxLenException;
use App\Exceptions\InvalidMinLenException;
use App\Exceptions\InvalidNegativeIntegerException;
use App\Exceptions\InvalidPositiveIntegerException;
use App\Exceptions\InvalidRealException;
use App\Exceptions\InvalidRoleException;
use App\Exceptions\InvalidSlugException;
use App\Exceptions\InvalidStringException;
use App\Exceptions\RequiredFieldException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidNumericException;
use DateTime;
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

  private function numeric(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[0-9]+$/', $value)) {
      throw new InvalidNumericException($field);
    }
  }

  private function real(string $field, float|int|string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (\is_string($value) && !preg_match('/^-?((0|[1-9][0-9]*)(\.[0-9]+)?|\.[0-9]+)$/', $value)) {
      throw new InvalidRealException($field);
    }

    if (!\is_int($value) && !\is_float($value)) {
      throw new InvalidRealException($field);
    }
  }

  private function integer(string $field, int|string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (\is_string($value) && !preg_match('/^(0|(-?[1-9][0-9]*))$/', $value)) {
      throw new InvalidIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidIntegerException($field);
    }
  }

  private function positive(string $field, int|string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (\is_string($value) && !preg_match('/^[1-9][0-9]*$/', $value)) {
      throw new InvalidPositiveIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidPositiveIntegerException($field);
    }
  }

  private function negative(string $field, int|string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (\is_string($value) && !preg_match('/^-[1-9][0-9]*$/', $value)) {
      throw new InvalidNegativeIntegerException($field);
    }

    if (!\is_int($value)) {
      throw new InvalidNegativeIntegerException($field);
    }
  }

  private function alpha(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[A-Za-z]+$/', $value)) {
      throw new InvalidAlphabeticOnlyException($field);
    }
  }

  private function alphaaccents(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[\p{L}]+$/u', $value)) {
      throw new InvalidAlphabeticWithAccentsException($field);
    }
  }

  private function alphaspaces(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
      throw new InvalidAlphabeticWithSpacesException($field);
    }
  }

  private function alphahifen(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[\p{L}-]+$/u', $value)) {
      throw new InvalidAlphabeticWithHifenException($field);
    }
  }

  private function alphabetic(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[\p{L}\s-]+$/u', $value)) {
      throw new InvalidAlphabeticException($field);
    }
  }

  private function alphanumeric(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[0-9A-Za-z]+$/', $value)) {
      throw new InvalidAlphaNumericException($field);
    }
  }

  private function min(string $field, string $value, array $params): void
  {
    if ($value === null || $value === '') {
      return;
    }

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
    if ($value === null || $value === '') {
      return;
    }

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

  private function string(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[\p{L}\p{N}\s\.\-\/ºª,\']+$/u', $value)) {
      throw new InvalidStringException($field);
    }
  }

  private function acronym(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[A-Z]{2,3}$/', $value)) {
      throw new InvalidStringException($field, "O campo deve conter apenas letras maiúsculas (2 ou 3 caracteres).");
    }
  }

  private function in(string $field, string $value, array $params): void
  {
    if (!\in_array($value, $params)) {
      throw new InvalidRoleException($field);
    }
  }

  private function date(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $value);
    if ($dt === false) {
      throw new InvalidDateException($field);
    }
  }

  public function boolean(string $field, mixed $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!\is_bool($value)) {
      throw new InvalidBoolException($field);
    }
  }

  public function slug(string $field, string $value): void
  {
    if ($value === null || $value === '') {
      return;
    }

    if (!preg_match('/^[a-z0-9-]+$/i', $value)) {
      throw new InvalidSlugException($field);
    }
  }
}