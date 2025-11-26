<?php

use App\Utils\Logger;

try {
  $router = require_once __DIR__ . '/../bootstrap.php';

  $router->run();
} catch (\Throwable $th) {
  new Logger()->error($th->getMessage());
}