<?php

namespace App\Database;

use App\Database\Method\Aggregation;
use App\Database\Method\Delete;
use App\Database\Method\Filter;
use App\Database\Method\Insert;
use App\Database\Method\Join;
use App\Database\Method\Limit;
use App\Database\Method\Pagination;
use App\Database\Method\Result;
use App\Database\Method\Select;
use App\Database\Method\Update;
use PDO;

class QueryBuilder
{
  use Aggregation, Delete, Filter, Insert, Join, Limit, Pagination, Result, Select, Update;

  private PDO $pdo;
  private array $params;
  private bool $mainCommandAreadyFilled = false;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  private function toSql(): string
  {
    $insertQuery = $this->getInsert();
    $selectQuery = $this->getSelect();
    $updateQuery = $this->getUpdate();
    $deleteQuery = $this->getDelete();

    [$mainCommand] = array_filter([
      $insertQuery,
      $selectQuery,
      $updateQuery,
      $deleteQuery,
    ], fn($command) => $command !== null);

    $this->params = array_filter([
      $this->dataToInsert,
      $this->dataToUpdate
    ], fn($data) => !empty($data));

    $joinQuery = $this->getJoin();
    $whereQuery = $this->getWhere();
    $groupQuery = $this->getGroup();
    $havingQuery = $this->getHaving();
    $orderQuery = $this->getOrder();
    $limitQuery = $this->getLimit();
    $offsetQuery = $this->getOffset();

    $sql = $mainCommand . ' ' . implode(' ', array_filter([
      $joinQuery,
      $whereQuery,
      $groupQuery,
      $havingQuery,
      $orderQuery,
      $limitQuery,
      $offsetQuery,
    ], fn($part) => $part !== null));

    $this->clearSql();

    return $sql;
  }

  private function clearSql(): void
  {
    $this->mainCommandAreadyFilled = false;

    $this->clearDelete();
    $this->clearGroup();
    $this->clearHaving();
    $this->clearInsert();
    $this->clearJoin();
    $this->clearLimit();
    $this->clearOder();
    $this->clearOffset();
    $this->clearSelect();
    $this->clearUpdate();
    $this->clearWhere();
  }
}