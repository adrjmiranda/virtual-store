<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Container;
use App\Core\ServiceProvider;
use App\Http\Handler\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();

ServiceProvider::register($container);

$router = new Router($container);

$router->setGlobalMiddlewares([
  'maintenance',
  'locale'
]);

require_once __DIR__ . '/routes/api.php';
require_once __DIR__ . '/routes/web.php';

return $router;

