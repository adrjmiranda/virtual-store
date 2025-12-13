<?php

namespace App\Exceptions;

class CouponUpdateException extends BusinessException
{
  public function __construct(string $field = "coupon_update", string $message = "Erro ao tentar atualizar coupon.")
  {
    parent::__construct($field, $message);
  }
}