<?php

namespace App\Database\Method;

use InvalidArgumentException;
use LogicException;

trait Select
{
  private ?string $table = null;
  private array $selectColumns = [];
  private bool $distinct = false;

  public function select(string|array $columns = ['*']): static
  {
    if ($this->mainCommandAreadyFilled) {
      throw new LogicException("You cannot call more than one main method per query.", 500);
    }

    $this->mainCommandAreadyFilled = true;

    if (empty($columns)) {
      throw new InvalidArgumentException("Column list cannot be empty.", 500);
    }

    $this->selectColumns = \is_array($columns) ? $columns : [$columns];

    return $this;
  }

  public function from(string $table): static
  {
    if (empty($table)) {
      throw new InvalidArgumentException("The table name must be provided.", 500);
    }

    $this->table = $table;

    return $this;
  }

  public function distinct(): static
  {
    $this->distinct = true;

    return $this;
  }

  private function getSelect(): ?string
  {
    if (!$this->table) {
      throw new LogicException("No table defined. Use ->from('table') before building the query.", 500);
    }

    $distinct = $this->distinct ? 'DISTINCT ' : '';
    $columns = implode(', ', $this->selectColumns);

    return empty($columns) ? null : "SELECT {$distinct}{$columns} FROM {$this->table}";
  }

  private function clearSelect(): void
  {
    $this->table = null;
    $this->selectColumns = [];
    $this->distinct = false;
  }
}