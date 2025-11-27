<?php

namespace App\Database\Method;

use InvalidArgumentException;

trait Limit
{
  private ?int $limit = null;
  private ?int $offset = null;

  public function limit(int $limit): static
  {
    if ($limit < 0) {
      throw new InvalidArgumentException('The limit cannot be less than zero.', 500);
    }

    $this->limit = $limit;

    return $this;
  }

  private function getLimit(): ?string
  {
    return $this->limit === null ? null : $this->limit;
  }

  public function offset(int $offset): static
  {
    if ($offset < 0) {
      throw new InvalidArgumentException('The offset cannot be less than zero.', 500);
    }

    $this->offset = $offset;

    return $this;
  }

  private function getOffset(): ?string
  {
    return $this->offset === null ? null : $this->offset;
  }
}