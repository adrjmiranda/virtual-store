<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\InventoryChanges\InventoryChange;
use App\Repository\Shared\BaseRepository;

class InventoryChangeRepository extends BaseRepository
{
  private const string TABLE = 'inventory_changes';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, InventoryChange::class);
  }
}