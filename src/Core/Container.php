<?php

namespace App\Core;

use Exception;
use Closure;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
  private array $bindings = [];
  private array $singletons = [];

  public function bind(string $abstract, callable|string $concrete): void
  {
    $this->bindings[$abstract] = $concrete;
  }

  public function singleton(string $abstract, callable|string $concrete): void
  {
    $this->singletons[$abstract] = $concrete;
  }

  public function make(string $class): mixed
  {
    if (isset($this->singletons[$class])) {
      if (is_object($this->singletons[$class])) {
        return $this->singletons[$class];
      }

      return $this->singletons[$class] = $this->resolve($this->singletons[$class]);
    }

    if (isset($this->bindings[$class])) {
      return $this->resolve($this->bindings[$class]);
    }

    return $this->resolve($class);
  }

  private function resolve(callable|string $concrete): mixed
  {
    if ($concrete instanceof Closure) {
      return $concrete($this);
    }

    if (!class_exists($concrete)) {
      throw new Exception("Class {$concrete} does not exist.", 500);
    }

    $reflection = new ReflectionClass($concrete);

    if (!$reflection->isInstantiable()) {
      throw new Exception("Class {$concrete} cannot be instantiated.", 500);
    }

    $constructor = $reflection->getConstructor();

    if (!$constructor) {
      return new $concrete;
    }

    $params = $constructor->getParameters();
    $dependences = [];

    foreach ($params as $param) {
      $type = $param->getType();

      if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
        $dependences[] = $this->make($type->getName());
      } elseif ($param->isDefaultValueAvailable()) {
        $dependences[] = $param->getDefaultValue();
      } else {
        throw new Exception("Could not resolve {$param->getName()} in {$reflection->getName()}", 500);
      }
    }

    return $reflection->newInstanceArgs($dependences);
  }
}