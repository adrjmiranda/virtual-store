<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\AuditLogs\AuditLog;
use App\Repository\Shared\BaseRepository;

class AuditLogRepository extends BaseRepository
{
  private const string TABLE = 'audit_logs';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, AuditLog::class);
  }
}