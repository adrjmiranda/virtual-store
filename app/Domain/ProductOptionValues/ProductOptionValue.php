<?php

namespace App\Domain\ProductOptionValues;

use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\OptionId;
use App\Domain\ValueObjects\Shared\DatabaseEntity;
use App\Domain\ValueObjects\UpdatedAt;

class ProductOptionValue extends DatabaseEntity
{
  public const array FIELDS_INSERT = [
    'option_id',
    'value',
  ];

  public function __construct(
    private ?Id $id,
    private OptionId $optionId,
    private Value $value,
    private ?CreatedAt $createdAt,
    private ?UpdatedAt $updatedAt
  ) {
  }
}