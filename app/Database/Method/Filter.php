<?php

namespace App\Database\Method;

trait Filter
{
  public function where(string $column, string $operator, mixed $value): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function orWhere(string $column, string $operator, mixed $value): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function whereIn(string $column, array $values): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function whereNotIn(string $column, array $values): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function whereNull(string $column): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function whereNotNull(string $column): static
  {
    // TODO:
    // ...
    return $this;
  }

  public function having(string $column, string $operator, mixed $value): static
  {
    // TODO:
    // ...
    return $this;
  }
}
