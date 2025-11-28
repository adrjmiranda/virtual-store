<?php

namespace App\Database\Method;

use InvalidArgumentException;

trait Aggregation
{
  private array $orderBy = [];
  private array $groupBy = [];
  private array $andHaving = [];
  private array $orHaving = [];


  public function orderBy(string $column, string $direction = 'ASC'): static
  {
    if (empty($column)) {
      throw new InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    if ($direction !== 'ASC' && $direction !== 'DESC') {
      throw new InvalidArgumentException("Passed invalid direction in orderBy function.", 500);
    }

    $this->orderBy[] = "{$column} {$direction}";

    return $this;
  }

  private function getOrder(): ?string
  {
    return empty($this->orderBy) ? null : 'ORDER BY ' . implode(', ', $this->orderBy);
  }

  public function groupBy(string|array $columns): static
  {
    if (empty($columns)) {
      throw new InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $this->groupBy = \is_array($columns) ? [...$this->groupBy, ...$columns] : [...$this->groupBy, $columns];

    return $this;
  }

  private function getGroup(): ?string
  {
    return empty($this->groupBy) ? null : 'GROUP BY ' . implode(', ', $this->groupBy);
  }


  public function andHaving(string $method, string $column, string $operator, mixed $value): static
  {
    if (empty($method)) {
      throw new InvalidArgumentException("You cannot pass an empty method name.", 500);
    }

    if (empty($column)) {
      throw new InvalidArgumentException("You cannot pass an empty column name.", 500);
    }


    if (empty($operator)) {
      throw new InvalidArgumentException("You cannot pass an empty operator.", 500);
    }


    $method = strtoupper($method);
    $this->andHaving[] = "{$method}($column) {$operator} {$value}";

    return $this;
  }

  public function orHaving(string $method, string $column, string $operator, mixed $value): static
  {
    if (empty($method)) {
      throw new InvalidArgumentException("You cannot pass an empty method name.", 500);
    }

    if (empty($column)) {
      throw new InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    if (empty($operator)) {
      throw new InvalidArgumentException("You cannot pass an empty operator.", 500);
    }


    $method = strtoupper($method);
    $this->orHaving[] = "{$method}($column) {$operator} {$value}";

    return $this;
  }

  private function getHaving(): ?string
  {
    if (empty($this->groupBy)) {
      return null;
    }

    $partAndHaving = implode(' AND ', $this->andHaving);
    $partOrHaving = implode(' OR ', $this->orHaving);

    $allHaving = [];
    if (!empty($partAndHaving)) {
      $allHaving[] = $partAndHaving;
    }

    if (!empty($partOrHaving)) {
      $allHaving[] = $partOrHaving;
    }

    $partHaving = implode(' OR ', $allHaving);

    return empty($partHaving) ? null : "HAVING $partHaving";
  }

  private function clearOder(): void
  {
    $this->orderBy = [];
  }

  private function clearGroup(): void
  {
    $this->groupBy = [];
  }

  private function clearHaving(): void
  {
    $this->andHaving = [];
    $this->orHaving = [];
  }
}