<?php

namespace App\Repository;

use App\Database\QueryBuilder;
use App\Domain\Images\Image;
use App\Repository\Shared\BaseRepository;

class ImageRepository extends BaseRepository
{
  private const string TABLE = 'images';

  public function __construct(protected QueryBuilder $db)
  {
    parent::__construct($db, self::TABLE, Image::class);
  }
}