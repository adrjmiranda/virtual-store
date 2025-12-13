<?php

namespace App\Exceptions;

class CouponCreationException extends BusinessException
{
  public function __construct(string $field = "coupon_create", string $message = "Erro ao tentar criar coupon.")
  {
    parent::__construct($field, $message);
  }
}