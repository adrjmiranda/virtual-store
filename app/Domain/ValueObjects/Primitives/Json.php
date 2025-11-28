<?php

namespace App\Domain\ValueObjects\Primitives;

use InvalidArgumentException;

abstract class Json
{
  private readonly array $value;

  public function __construct(array|string $value)
  {
    if (is_string($value)) {
      $decoded = json_decode($value, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new InvalidArgumentException("Invalid JSON string provided.", 500);
      }

      $this->value = $decoded;
    } elseif (\is_array($value)) {
      $this->value = $value;
    } else {
      throw new InvalidArgumentException("JsonValue must be array or JSON string.", 500);
    }
  }

  public function value(): array
  {
    return $this->value;
  }

  public function toJson(): string
  {
    return json_encode($this->value(), JSON_THROW_ON_ERROR);
  }

  public function equals(self $other): bool
  {
    return $this->toJson() === $other->toJson();
  }
}