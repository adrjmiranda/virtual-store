<?php

namespace App\Exceptions;

class CouponRemoveException extends BusinessException
{
  public function __construct(string $field = "coupon_remove", string $message = "Erro ao tentar remover coupon.")
  {
    parent::__construct($field, $message);
  }
}