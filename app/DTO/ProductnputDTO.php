<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class ProductnputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected $fieldPrefix = 'product';

  public function __construct(
    public ?string $name,
    public ?string $slug,
    public ?string $description,
    public ?int $price,
    public ?int $stock,
    public ?bool $isActive,
    public ?string $deletedAt
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'name' => 'trim|itrim|htmlspecialchars',
      'slug' => 'trim|itrim|htmlspecialchars',
      'description' => 'trim|itrim|htmlspecialchars',
      'price' => 'trim|itrim',
      'stock' => 'trim|itrim',
      'isActive' => 'noop',
      'deletedAt' => 'trim',
    ];
  }

  public function validations(): array
  {
    return [
      'name' => 'required|min:2|max:255',
      'slug' => 'required|max:272',
      'description' => 'string|max:1000',
      'price' => 'required|integer',
      'stock' => 'required|integer',
      'isActive' => 'required|boolean',
      'deletedAt' => 'date',
    ];
  }
}