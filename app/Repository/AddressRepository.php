<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Addresses\Address;
use App\Repository\Shared\BaseRepository;

class AddressRepository extends BaseRepository
{
  private const string TABLE = 'addresses';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Address::class);
  }

  public function forUser(int $userId): array
  {
    $rows = $this->db
      ->from(self::TABLE)
      ->andWhere('user_id', '=', $userId)
      ->get();

    return array_map([Address::class, 'fromDatabase'], $rows);
  }
}