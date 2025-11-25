<?php

namespace App\Http\Contracts;

use App\Http\Message\Request;
use App\Http\Message\Response;

interface MiddlewareInterface
{
  public function handler(Request $request, Response $response, callable $next): Response;
}