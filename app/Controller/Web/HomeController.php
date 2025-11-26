<?php

namespace App\Controller\Web;

use App\Http\Message\Response;

class HomeController extends Controller
{
  public function index(Response $response): Response
  {
    $view = $this->engine->render('pages/home', [
      'title' => 'Virtual Store'
    ]);
    $response->setBody($view);
    return $response;
  }
}