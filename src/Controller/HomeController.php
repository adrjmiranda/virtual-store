<?php

namespace App\Controller;

use App\Http\Response;

class HomeController
{
  public function index(Response $response): Response
  {
    $response->setBody('Homepage');
    return $response;
  }
}