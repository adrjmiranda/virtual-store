<?php

namespace App\Domain\AuditLogs;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\IpAddress;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class AuditLog extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private ?UserId $userId,
    private Action $action,
    private TableName $tableName,
    private RecordId $recordId,
    private ?Changes $changes,
    private ?IpAddress $ipAddress,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}