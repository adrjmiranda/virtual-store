<?php

namespace App\Controller\Web;

use App\Http\Message\Response;

class HomeController extends Controller
{
  public function index(Response $response): Response
  {
    $view = $this->view('pages/home', [
      'title' => 'Virtual Store'
    ], true);
    $response->setBody($view);
    return $response;
  }
}