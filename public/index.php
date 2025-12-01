<?php

use App\Exceptions\BusinessException;
use App\Http\Message\Error;
use App\Http\Message\Response;
use App\Utils\Flash;
use App\Utils\Logger;

try {
  $router = require_once __DIR__ . '/../bootstrap.php';
  $response = $router->run();
  $response->send();
} catch (BusinessException $e) {
  Flash::set($e->field, $e->getMessage());
  Response::back()->send();
} catch (\Throwable $th) {
  new Logger()->error($th->getMessage());
  $router = require_once __DIR__ . '/../bootstrap.php';
  $handler = $router->container()->make(Error::class);
  $response = $handler->render($th);

  $response->send();
}