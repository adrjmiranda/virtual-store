<?php

namespace App\DTO;

use App\Contracts\DTO\SanitizableDTO;
use App\Contracts\DTO\ValidatableDTO;

class OrderItemInputDTO extends BaseDTO implements SanitizableDTO, ValidatableDTO
{
  protected string $fieldPrefix = 'order_item';

  public function __construct(
    public ?int $orderId,
    public ?int $productId,
    public ?int $variantId,
    public ?int $quantity,
    public ?int $unitPrice,
    public ?int $discount,
  ) {
  }

  public function sanitizations(): array
  {
    return [
      'orderId' => 'trim|itrim|htmlspecialchars',
      'productId' => 'trim|itrim|htmlspecialchars',
      'variantId' => 'trim|itrim|htmlspecialchars',
      'quantity' => 'trim|itrim|htmlspecialchars',
      'unitPrice' => 'trim|itrim|htmlspecialchars',
      'discount' => 'trim|itrim|htmlspecialchars',
    ];
  }

  public function validations(): array
  {
    return [
      'orderId' => 'required|positive',
      'productId' => 'required|positive',
      'variantId' => 'positive',
      'quantity' => 'required|posandzero',
      'unitPrice' => 'required|posandzero',
      'discount' => 'required|posandzero',
    ];
  }
}