<?php

namespace App\Exceptions;

use DomainException;
use Throwable;

class BusinessException extends DomainException
{
  public function __construct(public ?string $field = null, string $message = "", int $code = 400, ?Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}