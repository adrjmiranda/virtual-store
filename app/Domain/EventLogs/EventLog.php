<?php

namespace App\Domain\EventLogs;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\IpAddress;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;
use App\Domain\ValueObjects\UserId;

class EventLog extends DatabaseEntity
{
  public function __construct(
    private ?Id $id,
    private ?UserId $userId,
    private Type $type,
    private ?Description $description,
    private ?IpAddress $ipAddress,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}