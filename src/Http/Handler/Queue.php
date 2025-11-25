<?php

namespace App\Http\Handler;

use App\Http\Message\Request;
use App\Http\Message\Response;

class Queue
{
  private array $middlewares;

  public function __construct(array $middlewares)
  {
    $this->middlewares = $middlewares;
  }

  public function dispatch(Request $request, Response $response, callable $handler): Response
  {
    $next = $handler;

    foreach (array_reverse($this->middlewares) as $middleware) {
      $current = $next;
      $next = fn($request): Response => (new $middleware())->handler($request, $response, $current);
    }

    return $next($request);
  }
}