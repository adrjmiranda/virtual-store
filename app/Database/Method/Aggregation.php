<?php

namespace App\Database\Method;

trait Aggregation
{
  public function orderBy(string $column, string $direction = 'ASC'): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function groupBy(string|array $columns): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function having(): static
  {
    // TODO:
    // ...
    return $this;
  }
}