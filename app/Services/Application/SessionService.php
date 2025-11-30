<?php

namespace App\Services\Application;

class SessionService
{
  private bool $started = false;

  public function __construct()
  {
    $this->start();
  }

  public function start(): void
  {
    if ($this->started) {
      return;
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    $this->started = true;
  }

  public function set(string $key, mixed $value): void
  {
    $_SESSION[$key] = $value;
  }

  public function get(string $key, mixed $default = null): mixed
  {
    return $_SESSION[$key] ?? $default;
  }

  public function has(string $key): bool
  {
    return isset($_SESSION[$key]);
  }

  public function delete(string $key): void
  {
    unset($_SESSION[$key]);
  }

  public function clear(): void
  {
    $_SESSION = [];
  }

  public function regenerate(): void
  {
    session_regenerate_id(true);
  }

  public function destroy(): void
  {
    $this->clear();

    if (session_status() === PHP_SESSION_ACTIVE) {
      session_destroy();
    }

    $this->started = false;
  }
}








