<?php

namespace App\Database\Method;

use InvalidArgumentException;
use LogicException;

trait Update
{
  private array $dataToUpdate = [];
  private ?string $setUpdate = null;

  public function update(array $data): static
  {
    if ($this->mainCommandAreadyFilled) {
      throw new LogicException("You cannot call more than one main method per query.", 500);
    }

    $this->mainCommandAreadyFilled = true;

    if (empty($data)) {
      throw new InvalidArgumentException("The data submitted for update must not be empty.", 500);
    }

    $this->dataToUpdate = $data;
    $columns = array_keys($data);
    $this->setUpdate = 'SET ' . implode(', ', array_map(fn($column) => "{$column} = :{$column}", $columns));

    return $this;
  }

  public function getUpdate(): ?string
  {
    if (!$this->table) {
      throw new LogicException("No table defined. Use ->from('table') before building the query.", 500);
    }

    return $this->setUpdate === null ? null : "UPDATE {$this->table} {$this->setUpdate}";
  }

  private function clearUpdate()
  {
    $this->dataToUpdate = [];
    $this->setUpdate = null;
  }
}