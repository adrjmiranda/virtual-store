<?php

namespace App\Http\Handler;

use App\Core\Container;
use App\Http\Message\Request;
use App\Http\Message\Response;

class Queue
{
  private Container $container;
  private array $middlewares;

  public function __construct(Container $container, array $middlewares)
  {
    $this->container = $container;
    $this->middlewares = $middlewares;
  }

  public function dispatch(Request $request, Response $response, callable $handler): Response
  {
    $next = $handler;

    foreach (array_reverse($this->middlewares) as $middleware) {
      $current = $next;
      $next = fn($request): Response => $this->container->make($middleware)->handler($request, $response, $current);
    }

    return $next($request);
  }
}