<?php

use App\Controller\HomeController;
use App\Http\Handler\Router;

/** @var Router $router */

$router->get('/', HomeController::class, 'index')->addMiddleware('maintenance', 'locale');
$router->group('/')
  ->get('login', HomeController::class, 'index')
  ->get('register', HomeController::class, 'index')
  ->addMiddleware('maintenance');