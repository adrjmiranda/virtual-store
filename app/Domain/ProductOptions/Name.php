<?php

namespace App\Domain\ProductOptions;

class Name extends \App\Domain\ValueObjects\Name
{
  protected int $maxLen = 100;
}