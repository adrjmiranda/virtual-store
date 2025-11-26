<?php

namespace App\Providers;

use App\Core\Container;
use App\Services\SessionService;
use App\Utils\Logger;

class AppServiceProvider
{
  public static function register(Container $container): void
  {
    // Singleton
    $container->singleton(Logger::class, Logger::class);

    // Bind
    $container->bind(SessionService::class, SessionService::class);
  }
}