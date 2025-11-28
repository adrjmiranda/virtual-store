<?php

namespace App\Domain\ValueObjects\Contracts;

interface DatabaseEntityInterface
{
  public static function fromDatabase(array $data): static;
  public function toDatabase(): array;
}