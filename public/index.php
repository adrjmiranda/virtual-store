<?php

use App\Utils\Logger;

try {
  $router = require_once __DIR__ . '/../bootstrap.php';
  $response = $router->run();
  $response->send();
} catch (\Throwable $th) {
  new Logger()->error($th->getMessage());
}