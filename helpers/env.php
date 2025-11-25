<?php

function env(string $key, mixed $default = null): mixed
{
  return $_ENV[$key] ?? $default;
}

function isMaintenance(): bool
{
  return env('APP_MODE') === 'maintenance';
}

function isDev(): bool
{
  return env('APP_ENV') === 'development';
}