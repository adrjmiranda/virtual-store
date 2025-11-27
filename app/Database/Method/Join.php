<?php

namespace App\Database\Method;

use App\Database\Type\JoinType;

trait Join
{
  private array $join = [];

  private function partOn(array $on, string $type = 'AND'): string
  {
    $type = strtoupper($type);
    $type = $type === 'OR' ? $type : 'AND';

    return empty($on) ? '' : implode(" {$type} ", array_map(fn($condition) => implode(' ', $condition), $on));
  }


  private function join(string $table, array $andOn, array $orOn, JoinType $type = JoinType::INNER): static
  {
    $partAndOn = $this->partOn($andOn);
    $partOrOn = $this->partOn($orOn);
    $partOrOn = !empty($partAndOn) ? "OR {$partOrOn}" : $partOrOn;

    $partOn = !empty($partAndOn) ? "{$partAndOn} {$partOrOn}" : $partOrOn;

    if (!\in_array($type, [JoinType::CROSS]) && empty(trim($partOn))) {
      throw new \InvalidArgumentException("A JOIN of type {$type->value} requires at least one ON condition.", 500);
    }

    $queryStart = "{$type->value} JOIN {$table}";
    $queryPartWithOn = "{$queryStart} {$partOn}";

    $queryPart = $type->value === JoinType::CROSS ? $queryStart : $queryPartWithOn;
    $this->join[] = $queryPart;

    return $this;
  }

  public function innerJoin(string $table, array $andOn = [], array $orOn = []): static
  {
    $this->join($table, $andOn, $orOn, JoinType::INNER);

    return $this;
  }

  public function leftJoin(string $table, array $andOn = [], array $orOn = []): static
  {
    $this->join($table, $andOn, $orOn, JoinType::LEFT);

    return $this;
  }

  public function rightJoin(string $table, array $andOn = [], array $orOn = []): static
  {
    $this->join($table, $andOn, $orOn, JoinType::RIGHT);

    return $this;
  }

  public function crossJoin(string $table, array $andOn = [], array $orOn = []): static
  {
    $this->join($table, $andOn, $orOn, JoinType::CROSS);

    return $this;
  }

  public function fullJoin(string $table, array $andOn = [], array $orOn = []): static
  {
    $this->join($table, $andOn, $orOn, JoinType::FULL);

    return $this;
  }

  public function getJoinPart(): string
  {
    return implode(' ', $this->join);
  }
}