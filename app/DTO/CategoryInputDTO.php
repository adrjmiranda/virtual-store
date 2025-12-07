<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class CategoryInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'category';

  public function __construct(
    public ?string $name,
    public ?string $slug,
    public ?string $description,
    public ?int $parentId,
    public ?bool $isActive,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'name' => 'trim|itrim|htmlspecialchars|stripslashes',
      'slug' => 'trim|itrim|htmlspecialchars|stripslashes',
      'description' => 'trim|itrim|htmlspecialchars|stripslashes',
      'parentId' => 'trim|itrim|htmlspecialchars',
      'isActive' => 'noop',
    ];
  }

  public function validations(): array
  {
    return [
      'name' => 'required|string|max:255',
      'slug' => 'required|slug|max:272',
      'description' => 'string|max:1000',
      'parentId' => 'positive',
      'isActive' => 'required|boolean',
    ];
  }
}