<?php

namespace App\Middleware;

use App\Http\Contracts\MiddlewareInterface;
use App\Http\Message\Request;
use App\Http\Message\Response;

class Locale implements MiddlewareInterface
{
  public function handler(Request $request, Response $response, callable $next): Response
  {
    return $next($request);
  }
}