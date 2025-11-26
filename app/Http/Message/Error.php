<?php

namespace App\Http\Message;

use App\Utils\Logger;
use League\Plates\Engine;

class Error
{
  private Engine $engine;
  private Logger $logger;

  public function __construct(Engine $engine, Logger $logger)
  {
    $this->engine = $engine;
    $this->logger = $logger;
  }

  public function render(\Throwable $e): Response
  {
    $this->logger->error(sprintf(
      "[%s] %s in %s:%d\nTrace:\n%s",
      $e->getCode(),
      $e->getMessage(),
      $e->getFile(),
      $e->getLine(),
      $e->getTraceAsString()
    ));

    $status = $e->getCode();
    if ($status < 400 || $status > 599) {
      $status = 500;
    }

    $response = new Response();
    $response->status($status);
    $view = 'errors/' . ($status === 404 ? '404' : ($status === 500 ? '500' : 'default'));
    $response->setBody($this->engine->render($view, [
      'status' => $status
    ]));

    return $response;
  }
}