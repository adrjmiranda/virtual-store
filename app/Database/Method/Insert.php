<?php

namespace App\Database\Method;

use InvalidArgumentException;
use LogicException;

trait Insert
{
  private array $dataToInsert = [];
  private ?string $insertColumns = null;
  private ?string $insertValues = null;

  public function insert(array $data): static
  {
    if (empty($data)) {
      throw new InvalidArgumentException("The data submitted for update must not be empty.", 500);
    }

    $this->dataToInsert = $data;
    $columns = array_keys($data);
    $this->insertColumns = '(' . implode(', ', $columns) . ')';
    $this->insertValues = '(:' . implode(', :', $columns) . ')';

    return $this;
  }

  private function getInsert(): ?string
  {
    if (!$this->table) {
      throw new LogicException("No table defined. Use ->from('table') before building the query.", 500);
    }

    return $this->insertColumns === null || $this->insertValues === null ? null : "INSERT INTO {$this->table} {$this->insertColumns} VALUES {$this->insertValues} RETURNING id";
  }
}