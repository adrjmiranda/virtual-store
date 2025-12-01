<?php

namespace App\Utils;

class Flash
{
  private const string FLASH_KEY = 'FLASH_KEY';

  public static function set(string $key, string $message): void
  {
    $_SESSION[self::FLASH_KEY][$key] = $message;
  }

  public static function get(string $key): ?string
  {
    $message = $_SESSION[self::FLASH_KEY][$key] ?? null;
    unset($_SESSION[self::FLASH_KEY][$key]);

    return $message;
  }

  public function remove(string $key): void
  {
    unset($_SESSION[self::FLASH_KEY][$key]);
  }

  public function clear(): void
  {
    unset($_SESSION[self::FLASH_KEY]);
  }
}