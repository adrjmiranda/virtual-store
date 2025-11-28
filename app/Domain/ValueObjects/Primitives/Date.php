<?php

namespace App\Domain\ValueObjects\Primitives;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

abstract class Date
{
  private const string DEFAULT_FORMAT = 'Y-m-d H:i:s';

  private readonly DateTimeImmutable $value;

  public function __construct(string|DateTimeInterface $value)
  {
    if (is_string($value)) {
      $date = DateTimeImmutable::createFromFormat(self::DEFAULT_FORMAT, $value);
      if (!$date) {
        throw new InvalidArgumentException("Invalid datetime format, expected YYYY-MM-DD HH:MM:SS", 500);
      }

      $this->value = $date;
    } elseif ($value instanceof DateTimeInterface) {
      $this->value = new DateTimeImmutable($value->format(self::DEFAULT_FORMAT));
    } else {
      throw new InvalidArgumentException("Invalid type for datetime.", 500);
    }
  }

  public function value(): DateTimeImmutable
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return $this->value()->format(self::DEFAULT_FORMAT) === $other->value()->format(self::DEFAULT_FORMAT);
  }
}