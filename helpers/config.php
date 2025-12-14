<?php

function config(string $filename): array
{
  return require filepath("config.{$filename}");
}

function phpIniToBytes(string $value): int
{
  $value = trim($value);
  $unit = strtolower(substr($value, -1));
  $bytes = (int) $value;

  return match ($unit) {
    'g' => $bytes * 1024 * 1024 * 1024,
    'm' => $bytes * 1024 * 1024,
    'k' => $bytes * 1024,
    default => $bytes,
  };
}
