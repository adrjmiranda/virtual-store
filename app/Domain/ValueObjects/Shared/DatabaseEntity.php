<?php

namespace App\Domain\ValueObjects\Shared;

use Exception;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

abstract class DatabaseEntity
{
  public function __call($method, $args)
  {
    $reflection = new ReflectionClass($this);

    $props = array_map(fn($p) => $p->getName(), $reflection->getProperties(ReflectionProperty::IS_PRIVATE));

    if (str_ends_with($method, 'Value')) {
      $prop = substr($method, 0, -5);

      if (!\in_array($prop, $props, true)) {
        throw new Exception("Invalid method '{$method}' in " . static::class, 500);
      }

      return $this->$prop?->value();
    }

    if (\in_array($method, $props, true)) {
      return $this->$method;
    }

    throw new Exception("Invalid method '{$method}' in " . static::class, 500);
  }

  public static function partial(array $data): static
  {
    $class = new ReflectionClass(static::class);
    $entity = $class->newInstanceWithoutConstructor();

    foreach ($data as $field => $value) {
      $propertyName = snakeToCamel($field);

      if (!$class->hasProperty($propertyName)) {
        continue;
      }

      $property = $class->getProperty($propertyName);
      $type = $property->getType();

      if ($type instanceof ReflectionUnionType) {
        $namedTypes = array_filter(
          $type->getTypes(),
          fn($t) => !$t->isBuiltin()
        );
        $type = reset($namedTypes) ?: null;
      }

      if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {

        $voClass = $type->getName();

        if ($value === null) {
          $entity->$propertyName = null;
          continue;
        }

        $voReflection = new ReflectionClass($voClass);
        $constructor = $voReflection->getConstructor();
        $params = $constructor?->getParameters();

        if ($params && count($params) === 1) {
          $paramType = $params[0]->getType();

          if (
            $paramType instanceof ReflectionNamedType &&
            enum_exists($paramType->getName())
          ) {
            $enumClass = $paramType->getName();
            $entity->$propertyName = new $voClass($enumClass::from($value));
            continue;
          }
        }

        $entity->$propertyName = new $voClass($value);
        continue;
      }

      $entity->$propertyName = $value;
    }

    return $entity;
  }



  public static function fromDatabase(array $data): static
  {
    $reflection = new ReflectionClass(static::class);
    $constructor = $reflection->getConstructor();
    $params = $constructor->getParameters();

    $dependences = [];

    foreach ($params as $param) {
      $type = $param->getType();

      if ($type instanceof ReflectionUnionType) {
        $namedTypes = array_filter(
          $type->getTypes(),
          fn($t) => !$t->isBuiltin()
        );
        $type = reset($namedTypes);
      }

      if ($type instanceof ReflectionNamedType && $type->isBuiltin()) {
        $field = camelToSnake($param->getName());
        $dependences[] = $data[$field] ?? null;
        continue;
      }

      $typeName = $type->getName();

      $filedClassName = basename(str_replace('\\', '/', $typeName));
      $field = camelToSnake($filedClassName);

      if (!\array_key_exists($field, $data)) {
        $dependences[] = null;
        continue;
      }

      $value = $data[$field];

      if ($value === null) {
        $dependences[] = null;
        continue;
      }

      $voReflection = new ReflectionClass($typeName);
      $voConstructor = $voReflection->getConstructor();

      if ($voConstructor && $voConstructor->getNumberOfParameters() === 1) {
        $innerParam = $voConstructor->getParameters()[0];
        $innerType = $innerParam->getType();

        if ($innerType instanceof ReflectionNamedType) {
          $innerTypeName = $innerType->getName();

          if (enum_exists($innerTypeName)) {
            $enumValue = $innerTypeName::from($value);
            $dependences[] = new $typeName($enumValue);
            continue;
          }
        }
      }

      $dependences[] = new $typeName($value);
    }

    return $reflection->newInstanceArgs($dependences);
  }


  public function toDatabase(array $fields): array
  {
    $data = [];

    foreach ($fields as $field) {
      $property = snakeToCamel($field);

      if (!property_exists($this, $property)) {
        continue;
      }

      $valueObject = $this->$property;

      if ($valueObject === null) {
        $data[$field] = null;
        continue;
      }

      if (\is_object($valueObject) && method_exists($valueObject, 'value')) {
        $val = $valueObject->value();
        $data[$field] = $val instanceof \BackedEnum ? $val->value : $val;
        continue;
      }

      if (\is_object($valueObject)) {
        throw new \RuntimeException(
          "ValueObject '" . get_class($valueObject) . "' não possui método value()."
        );
      }

      $data[$field] = $valueObject;
    }

    return $data;
  }
}