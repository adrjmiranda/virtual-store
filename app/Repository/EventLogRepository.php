<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\EventLogs\EventLog;
use App\Repository\Shared\BaseRepository;

class EventLogRepository extends BaseRepository
{
  private const string TABLE = 'event_logs';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, EventLog::class);
  }
}