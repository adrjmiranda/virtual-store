<?php

namespace App\Exceptions;

class PaymentRemoveException extends BusinessException
{
  public function __construct(string $field = "payment_remove", string $message = "Erro ao tentar remover pagamento.")
  {
    parent::__construct($field, $message);
  }
}