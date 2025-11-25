<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Controller\HomeController;
use App\Http\Handler\Router;

$router = new Router();

$router->get('/', HomeController::class, 'index')->addMiddleware('maintenance', 'locale');
$router->group('/')
  ->get('login', HomeController::class, 'index')
  ->get('register', HomeController::class, 'index')
  ->addMiddleware('maintenance');

dd($router->paths);

$router->run();