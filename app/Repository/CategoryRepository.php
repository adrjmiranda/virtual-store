<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Categories\Category;
use App\Repository\Shared\BaseRepository;

class CategoryRepository extends BaseRepository
{
  private const string TABLE = 'categories';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Category::class);
  }
}