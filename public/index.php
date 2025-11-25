<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Http\Router;
use App\Controller\HomeController;

$router = new Router();

$router->get('/', HomeController::class, 'index');

$router->run();