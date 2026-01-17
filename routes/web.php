<?php

use App\Controller\Web\HomeController;
use App\Controller\Web\UserController;
use App\Http\Handler\Router;

/** @var Router $router */

$router->get('/', HomeController::class, 'index')->addMiddleware('maintenance', 'locale');
$router->group('/')
  ->get('login', UserController::class, 'login')
  ->get('register', UserController::class, 'register')
  ->addGroupMiddleware('maintenance');