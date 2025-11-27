<?php

namespace App\Database\Method;

use InvalidArgumentException;

trait Pagination
{
  public function paginate(int $perPage, int $page = 1): static
  {
    if ($perPage <= 0) {
      throw new InvalidArgumentException('The perPage value must be greater than zero.', 500);
    }

    if ($page < 1) {
      throw new InvalidArgumentException('The page value must be at last 1.', 500);
    }

    $offset = ($page - 1) * $perPage;

    $this->limit($perPage);
    $this->offset($offset);

    return $this;
  }
}