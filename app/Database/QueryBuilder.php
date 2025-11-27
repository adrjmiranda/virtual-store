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

class QueryBuilder
{
  private string $table;
  private string $select = '';


  private ?int $limit = null;
  private ?string $orderBy = null;
  private ?string $groupBy = null;

  use Aggregation, Delete, Filter, Insert, Join, Limit, Pagination, Result, Select, Update;
}