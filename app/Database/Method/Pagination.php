<?php

namespace App\Database\Method;

trait Pagination
{
  public function paginate(int $perPage, int $page = 1): static
  {
    // TODO:
    // ...
    return $this;
  }
}