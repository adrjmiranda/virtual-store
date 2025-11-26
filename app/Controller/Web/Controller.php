<?php

namespace App\Controller\Web;

use League\Plates\Engine;

abstract class Controller
{
  protected Engine $engine;

  public function __construct(Engine $engine)
  {
    $this->engine = $engine;
  }
}