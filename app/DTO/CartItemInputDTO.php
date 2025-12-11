<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class CartItemInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'cart_item';

  public function __construct(
    public ?int $cartId,
    public ?int $productId,
    public ?int $variantId,
    public ?int $quantity,
    public ?int $price,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'cartId' => 'noop',
      'productId' => 'noop',
      'variantId' => 'noop',
      'quantity' => 'noop',
      'price' => 'noop',
    ];
  }

  public function validations(): array
  {
    return [
      'cartId' => 'required|positive',
      'productId' => 'required|positive',
      'variantId' => 'positive',
      'quantity' => 'required|posandzero',
      'price' => 'required|posandzero',
    ];
  }
}