<?php

function snakeToCamel(string $value): string
{
  return lcfirst(snakeToUpperCamel($value));
}

function snakeToUpperCamel(string $value): string
{
  return str_replace('_', '', ucwords($value, '_'));
}

function camelToSnake(string $value): string
{
  $value = preg_replace('/(.)([A-Z][a-z]+)/', '$1_$2', $value);
  $value = preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $value);
  return strtolower($value);
}

