<?php

namespace App\Database\Method;

trait Select
{
  public function select(string|array $columns = ['*']): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function from(string $table): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function distinct(): static
  {
    // TODO:
    // ...
    return $this;
  }
}