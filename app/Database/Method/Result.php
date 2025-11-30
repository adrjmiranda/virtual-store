<?php

namespace App\Database\Method;

use InvalidArgumentException;
use LogicException;
use PDO;

trait Result
{
  public function execute(): bool
  {
    try {
      $sql = $this->toSql();
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($this->params);

      return $stmt->rowCount() !== 0;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function get(): array
  {
    try {
      $sql = $this->toSql();
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($this->params);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function first(): ?array
  {
    try {
      $this->limit(1);

      $sql = $this->toSql();
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($this->params);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ?: null;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  private function operationResult(string $column, string $method): int
  {
    try {
      if (!$this->table) {
        throw new LogicException("No table defined. Use ->from('table') before building the query.", 500);
      }

      if (empty($method)) {
        throw new InvalidArgumentException("The method cannot be empty.", 500);
      }

      $method = strtoupper($method);

      if (empty($column)) {
        throw new InvalidArgumentException("The column passed to the function {$method} must not be empty.", 500);
      }


      $sql = "SELECT {$method}($column) AS result FROM {$this->table}";

      $partWhere = $this->getWhere();
      if ($partWhere !== null) {
        $sql .= " {$partWhere}";
      }

      $stmt = $this->pdo->prepare($sql);

      return match ($method) {
        'COUNT' => (int) $stmt->fetchColumn(),
        'default' => (float) $stmt->fetchColumn(),
      };
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function count(): int
  {
    return $this->operationResult('*', 'count');
  }

  public function countDistinct(string $column): int
  {
    return $this->operationResult("DISTINCT {$column}", 'count');
  }

  public function sum(string $column): int
  {
    return $this->operationResult($column, 'sum');
  }

  public function max(string $column): int
  {
    return $this->operationResult($column, 'max');
  }

  public function min(string $column): int
  {
    return $this->operationResult($column, 'min');
  }

  public function avg(string $column): float
  {
    return $this->operationResult($column, 'avg');
  }

  public function exists(): bool
  {
    try {
      $this->limit(1);

      $sql = $this->toSql();

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($this->params);

      return (bool) $stmt->fetchColumn();
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}