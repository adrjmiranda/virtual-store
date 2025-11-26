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
  private array $instances = [];

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
    if (isset($this->instances[$class])) {
      return $this->instances[$class];
    }

    if (isset($this->singletons[$class])) {
      $object = $this->resolve($this->singletons[$class]);
      return $this->instances[$class] = $object;
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

      if (!$type) {
        if ($param->isDefaultValueAvailable()) {
          $dependences[] = $param->getDefaultValue();
          continue;
        }

        throw new Exception("Cannot resolve parameter \${$param->getName()} in {$reflection->getName()}", 500);
      }

      if (
        $type instanceof ReflectionNamedType
      ) {
        $typeName = $type->getName();

        if ($type->isBuiltin() || interface_exists($typeName) && new ReflectionClass($typeName)->isInternal()) {
          if ($param->isDefaultValueAvailable()) {
            $dependences[] = $param->getDefaultValue();
            continue;
          }

          throw new Exception("Cannot auto-wire internal type {$typeName} for parameter \${$param->getName()}", 500);
        }

        $dependences[] = $this->make($typeName);
        continue;
      }

      throw new Exception("Unable to resolve parameter \${$param->getName()} in {$reflection->getName()}", 500);
    }

    return $reflection->newInstanceArgs($dependences);
  }
}