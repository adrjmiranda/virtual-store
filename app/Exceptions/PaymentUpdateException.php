<?php

namespace App\Exceptions;

class PaymentUpdateException extends BusinessException
{
  public function __construct(string $field = "payment_update", string $message = "Erro ao tentar atualizar pagamento.")
  {
    parent::__construct($field, $message);
  }
}