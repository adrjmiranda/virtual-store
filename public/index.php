<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Controller\HomeController;
use App\Http\Handler\Router;

$router = new Router();

$router->get('/', HomeController::class, 'index')->addMiddleware('maintenance', 'locale');


$router->run();