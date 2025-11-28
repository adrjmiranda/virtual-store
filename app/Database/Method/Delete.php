<?php

namespace App\Database\Method;

use LogicException;

trait Delete
{
  private ?string $delete = null;

  public function delete(): static
  {
    if ($this->mainCommandAreadyFilled) {
      throw new LogicException("You cannot call more than one main method per query.", 500);
    }

    $this->mainCommandAreadyFilled = true;

    if (!$this->table) {
      throw new LogicException("No table defined. Use ->from('table') before building the query.", 500);
    }

    $this->delete = "DELETE FROM {$this->table}";

    return $this;
  }

  private function getDelete(): ?string
  {
    return $this->delete ?? null;
  }

  private function clearDelete(): void
  {
    $this->delete = null;
  }
}