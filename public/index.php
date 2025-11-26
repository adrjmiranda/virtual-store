<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Controller\HomeController;
use App\Core\Container;
use App\Http\Handler\Router;
use App\Utils\Logger;

try {
  $container = new Container();

  $router = new Router($container);
  $router->setGlobalMiddlewares([
    'maintenance',
    'locale'
  ]);

  $router->get('/', HomeController::class, 'index')->addMiddleware('maintenance', 'locale');
  $router->group('/')
    ->get('login', HomeController::class, 'index')
    ->get('register', HomeController::class, 'index')
    ->addMiddleware('maintenance');

  $router->run();
} catch (\Throwable $th) {
  $logger = new Logger();
  $logger->error($th->getMessage());
}