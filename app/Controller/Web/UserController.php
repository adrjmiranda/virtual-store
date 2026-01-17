<?php

namespace App\Controller\Web;

use App\Http\Message\Response;

class UserController extends Controller
{
  public function login(Response $response): Response
  {
    $view = $this->view('pages.login');
    $response->setBody($view);
    return $response;
  }

  public function register(Response $response): Response
  {
    $view = $this->view('pages.register');
    $response->setBody($view);
    return $response;
  }
}