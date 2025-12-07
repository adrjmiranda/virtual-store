<?php

namespace App\Infrastructure\Sanitizations;

trait Methods
{
  private function itrim(string $value): string
  {
    return preg_replace('/\s+/', ' ', $value);
  }

  private function noop(mixed $value): mixed
  {
    return $value;
  }
}