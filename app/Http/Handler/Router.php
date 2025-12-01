<?php

namespace App\Http\Handler;

use App\Core\Container;
use App\Http\Contracts\MiddlewareInterface;
use App\Http\Message\Request;
use App\Http\Message\Response;
use App\Http\Message\Error;
use Exception;

class Router
{
  private Container $container;

  private Request $request;
  private Response $response;

  private array $paths = [];
  private array $enableMethods;

  private string $currentMethod;
  private string $currentPath;

  private string $prefix = '';

  private static array $globalMiddlewares = [];

  public function __construct(
    Container $container,
    array $enableMethods = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS']
  ) {
    $this->container = $container;
    $this->enableMethods = $enableMethods;
    $this->request = new Request();
    $this->response = new Response();
  }

  public function container(): Container
  {
    return $this->container;
  }

  private function methodAllowed(string $httpMethod): bool
  {
    return \in_array($httpMethod, $this->enableMethods);
  }

  public function __call(string $method, array $args): self
  {
    $httpMethod = strtoupper($method);
    if (!$this->methodAllowed($httpMethod)) {
      throw new Exception("Method {$httpMethod} not allowed in Router", 500);
    }

    [$path, $controller, $handler] = $args;

    if (empty($path) || empty($controller) || empty($handler)) {
      throw new Exception("The [{$method}] method expects three parameters: path, controller, and handler.", 500);
    }

    if (!\is_string($path)) {
      throw new Exception("The route path cannot be a string.", 500);
    }

    if (!class_exists($controller)) {
      throw new Exception("The class [{$controller}] does not exist in the route [{$path}] definition.", 500);
    }

    if (!method_exists($controller, $handler)) {
      throw new Exception("The method [{$method}] does not exist in class [{$controller}].", 500);
    }

    $path = "{$this->prefix}/{$path}";

    $this->addPath($httpMethod, $this->sanitizePath($path), $controller, $handler);

    return $this;
  }

  private function sanitizePath(string $path): string
  {
    $path = preg_replace('/\/+/', '/', $path);
    $path = substr($path, -1) === '/' ? $path : "{$path}/";

    return $path;
  }

  private function pathToRegex(string $path): string
  {
    $path = str_replace('/', '\/', $path);

    return '/^' . preg_replace('/(\/\{[^\}]+\})+/', '/.+', preg_replace('/(\/\[[^\]]+\])+/', '/.*', $path)) . '$/';
  }

  private function checkOptionalParameters(string $path): void
  {
    $segments = array_values(array_filter(explode('/', $path)));
    $optionalParameterAlreadyExists = false;
    foreach ($segments as $segment) {
      if (preg_match('/\[[^\]]+\]/', $segment)) {
        $optionalParameterAlreadyExists = true;
      } else if ($optionalParameterAlreadyExists) {
        throw new Exception("Optional parameters should be at the end.", 500);
      }
    }
  }

  private function checkRouteConflict(string $definedPath, string $path): void
  {
    if (preg_match($definedPath, $path)) {
      throw new Exception("Route [{$path}] conflicts with already defined route [{$definedPath}].", 1);
    }
  }

  private function parameters(string $path): array
  {
    preg_match_all('/\{(.*?)\}/', $path, $requiredMatches);
    preg_match_all('/\[(.*?)\]/', $path, $optionalMatches);

    return array_merge($requiredMatches[1], $optionalMatches[1]);
  }

  private function addPath(string $httpMethod, string $path, string $controller, string $handler): void
  {
    $this->checkOptionalParameters($path);

    if (isset($this->paths[$httpMethod])) {
      $httpMethodRoutes = array_keys($this->paths[$httpMethod]);
      foreach ($httpMethodRoutes as $definedPath) {
        $this->checkRouteConflict($definedPath, $path);
      }
    }

    $pathRegex = $this->pathToRegex($path);

    $this->paths[$httpMethod][$pathRegex] = [
      'controller' => $controller,
      'handler' => $handler,
      'parameters' => $this->parameters($path)
    ];

    $this->currentPath = $pathRegex;
    $this->currentMethod = $httpMethod;
  }

  public function group(string $prefix): self
  {
    if (empty($prefix)) {
      throw new Exception("The route prefix cannot be empty.", 500);
    }
    $this->prefix = $prefix;

    return $this;
  }

  private function correspondingMiddleware(string $name): string
  {
    $all = config('middlewares');
    if (!\in_array($name, array_keys($all))) {
      throw new Exception("Middleware [{$name}] is not registered", 500);
    }

    $middleware = $all[$name];
    if (!class_exists($middleware)) {
      throw new Exception("The middleware class [{$middleware}] does not exist.t", 500);
    }

    $implements = class_implements($middleware);
    if (!\in_array(MiddlewareInterface::class, $implements)) {
      throw new Exception('The route middleware must implement ' . MiddlewareInterface::class, 500);
    }

    return $middleware;
  }

  public function addMiddleware(string ...$names): void
  {
    foreach ($names as $name) {
      $this->paths[$this->currentMethod][$this->currentPath]['middlewares'][$name] = $this->correspondingMiddleware($name);
    }

    $this->paths[$this->currentMethod][$this->currentPath]['middlewares'] = array_merge(
      self::$globalMiddlewares,
      $this->paths[$this->currentMethod][$this->currentPath]['middlewares'] ?? []
    );
  }

  public function setGlobalMiddlewares(array $names): void
  {
    foreach ($names as $name) {
      self::$globalMiddlewares[$name] = $this->correspondingMiddleware($name);
    }
  }

  public function run(): Response
  {
    try {
      $uri = $this->sanitizePath($this->request->uri());
      $httpMethod = $this->request->method();

      if (!$this->methodAllowed($httpMethod)) {
        throw new Exception("Method {$httpMethod} not enabled", 405);
      }

      $httpMethodRoutes = array_keys($this->paths[$httpMethod]);
      foreach ($httpMethodRoutes as $path) {
        if (preg_match($path, $uri)) {
          $matchPath = $this->paths[$httpMethod][$path];

          $controller = $matchPath['controller'];
          $handler = $matchPath['handler'];
          $parameters = $matchPath['parameters'];
          $middlewares = $matchPath['middlewares'];

          $response = (new Queue(
            $this->container,
            $middlewares
          ))->dispatch(
              $this->request,
              $this->response,
              fn(): Response => $this->container->make($controller)->$handler($this->response, $this->request, $parameters)
            );

          return $response;
        }
      }

      throw new Exception("Route not found", 404);
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}