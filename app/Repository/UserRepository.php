<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Users\User;
use App\Repository\Shared\BaseRepository;

class UserRepository extends BaseRepository
{
  private const string TABLE = 'users';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, User::class);
  }
}