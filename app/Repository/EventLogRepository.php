<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\EventLogs\EventLog;
use App\Domain\EventLogs\Type;
use App\Domain\ValueObjects\Description;
use App\Domain\ValueObjects\Enum\EventType;
use App\Domain\ValueObjects\UserId;
use App\Http\Message\Request;
use App\Repository\Shared\BaseRepository;
use App\Services\Application\SessionService;

class EventLogRepository extends BaseRepository
{
  private const string TABLE = 'event_logs';

  public function __construct(
    protected QueryBuilder $db,
    private SessionService $session,
    private Request $request
  ) {
    parent::__construct($db, self::TABLE, EventLog::class);
  }

  public function record(EventType $type, ?string $description = null): ?int
  {

    $this->insert(new EventLog(
      null,
      $this->session->get('user') === null ? null : new UserId((int) $this->session->get('user')['id']),
      new Type($type),
      $description === null ? new Description($description) : null,
      $this->request->clientIp(),
      null,
      null
    ), EventLog::FIELDS_INSERT);
  }
}