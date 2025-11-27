<?php

namespace App\Database\Method;

trait Filter
{
  private array $andWhere = [];
  private array $orWhere = [];
  private array $andWhereIn = [];
  private array $orWhereIn = [];
  private array $andWhereNotIn = [];
  private array $orWhereNotIn = [];
  private array $andWhereNull = [];
  private array $orWhereNull = [];
  private array $andWhereNotNull = [];
  private array $orWhereNotNull = [];

  public function andWhere(string $column, string $operator, mixed $value): static
  {
    if (empty($column)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    if (empty($operator)) {
      throw new \InvalidArgumentException("You cannot pass an empty operator.", 500);
    }

    $this->andWhere[] = "{$column} {$operator} {$value}";

    return $this;
  }

  public function orWhere(string $column, string $operator, mixed $value): static
  {
    if (empty($column)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }


    if (empty($operator)) {
      throw new \InvalidArgumentException("You cannot pass an empty operator.", 500);
    }

    $this->orWhere[] = "{$column} {$operator} {$value}";

    return $this;
  }

  public function andWhereIn(string $column, array $values): static
  {
    $list = implode(', ', $values);
    $this->andWhereIn[] = "{$column} IN ({$list})";

    return $this;
  }

  public function orWhereIn(string $column, array $values): static
  {
    if (empty($column)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $list = implode(', ', $values);
    $this->orWhereIn[] = "{$column} IN ({$list})";

    return $this;
  }

  public function andWhereNotIn(string $column, array $values): static
  {
    if (empty($column)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $list = implode(', ', $values);
    $this->andWhereNotIn[] = "{$column} NOT IN ({$list})";

    return $this;
  }

  public function orWhereNotIn(string $column, array $values): static
  {
    if (empty($column)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $list = implode(', ', $values);
    $this->orWhereNotIn[] = "{$column} NOT IN ({$list})";

    return $this;
  }

  public function andWhereNull(string|array $columns): static
  {
    if (empty($columns)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $this->andWhereNull[] = \is_array($columns) ? implode(' AND ', array_map(fn($column) => "$column IS NULL", $columns)) : "{$columns} IS NULL";

    return $this;
  }

  public function orWhereNull(string|array $columns): static
  {
    if (empty($columns)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $this->orWhereNull[] = \is_array($columns) ? implode(' OR ', array_map(fn($column) => "$column IS NULL", $columns)) : "{$columns} IS NULL";

    return $this;
  }

  public function andWhereNotNull(string|array $columns): static
  {
    if (empty($columns)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $this->andWhereNotNull[] = \is_array($columns) ? implode(' OR ', array_map(fn($column) => "$column IS NOT NULL", $columns)) : "{$columns} IS NOT NULL";

    return $this;
  }

  public function orWhereNotNull(string|array $columns): static
  {
    if (empty($columns)) {
      throw new \InvalidArgumentException("You cannot pass an empty column name.", 500);
    }

    $this->orWhereNotNull[] = \is_array($columns) ? implode(' OR ', array_map(fn($column) => "$column IS NOT NULL", $columns)) : "{$columns} IS NOT NULL";

    return $this;
  }

  private function getWhere(): ?string
  {
    $andWheres = implode(' AND ', array_map(fn($whereItem) => implode(' AND ', $whereItem), [
      $this->andWhere,
      $this->andWhereIn,
      $this->andWhereNotIn,
      $this->andWhereNull,
      $this->andWhereNotNull,
    ]));

    $orWheres = implode(' OR ', array_map(fn($whereItem) => implode(' OR ', $whereItem), [
      $this->orWhere,
      $this->orWhereIn,
      $this->orWhereNotIn,
      $this->orWhereNull,
      $this->orWhereNotNull,
    ]));

    $allWhere = [];
    if (!empty($andWheres)) {
      $allWhere[] = $andWheres;
    }

    if (!empty($orWheres)) {
      $allWhere[] = $orWheres;
    }

    return empty($allWhere) ? null : 'WHERE ' . implode(' OR ', $allWhere);
  }
}
