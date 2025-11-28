<?php

namespace App\Domain\ValueObjects\Shared;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;

abstract class DatabaseEntity
{
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
        $typeName = $type->getName();

        $entity->$propertyName = $value !== null
          ? new $typeName($value)
          : null;

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
        $namedTypes = array_filter($type->getTypes(), fn($t) => !$t->isBuiltin());
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

      if (!\array_key_exists($field, $data) || $data[$field] === null) {
        $dependences[] = null;
        continue;
      }

      $dependences[] = new $typeName($data[$field]);
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
        $data[$field] = $valueObject->value();
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