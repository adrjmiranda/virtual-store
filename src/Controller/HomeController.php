<?php

namespace App\Controller;

use App\Http\Message\Response;

class HomeController
{
  public function index(Response $response): Response
  {
    $response->setBody('Homepage');
    return $response;
  }
}