<?php

namespace App\Exceptions;

class PaymentCreationException extends BusinessException
{
  public function __construct(string $field = "payment_create", string $message = "Erro ao tentar criar pagamento.")
  {
    parent::__construct($field, $message);
  }
}